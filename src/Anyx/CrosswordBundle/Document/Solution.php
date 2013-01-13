<?php
/**
 *
 */
namespace Anyx\CrosswordBundle\Document;

/**
 *
 */
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

use JMS\Serializer\Annotation as Serializer;

/**
 * @MongoDB\Document(repositoryClass="Anyx\CrosswordBundle\Repository\SolutionRepository")
 * 
 */
class Solution {

	/**
	 * @MongoDB\Id
     * @Serializer\Groups({"solving","profile"})
	 */
	protected $id;

    /**
	 * @MongoDB\ReferenceOne(targetDocument="User")
	 * 
	 * @Serializer\Exclude
	 */
	protected $user;

	/**
	 * @MongoDB\ReferenceOne(targetDocument="Crossword")
     * @Serializer\Groups({"profile"})
	 */
	protected $crossword;

	/**
	 * @MongoDB\Date
     * @Serializer\Groups({"solving","profile"})
	 */
	protected $createdAt;

	/**
	 * @MongoDB\Date
     * @Serializer\Groups({"solving","profile"})
	 */
	protected $updatedAt;

	/**
	 * @MongoDB\EmbedMany(targetDocument="Answer")
	 * 
     * @Serializer\Groups({"solving"})
	 */
	protected $answers;

    /**
     *
     */
    public function getId() {
        return $this->id;
    }
        
	/**
	 * Events
	 */

	/**
	 * @MongoDB\PreUpdate
	 */
	public function setUpdatedAt() {
		$this->updatedAt = new \DateTime();
	}

	/**
	 * @MongoDB\PrePersist
	 */
	public function setCreatedAt() {
		$this->createdAt = $this->updatedAt = new \DateTime();
	}

	/**
	 * @param Crossword $crossword
	 */
	public function setCrossword( Crossword $crossword) {
		$this->crossword = $crossword;
	}

	/**
	 *
	 */
	public function getCrossword() {
		return $this->crossword;
	}

	/**
	 * @param User $user
	 */
	public function setUser( User $user ) {
		$this->user = $user;
	}

	/**
	 *
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param $answers
	 */
	public function setAnswers( $answers ) {
		if ( !empty( $this->answers ) ) {
			$this->updateAnswers( $answers );
		} else {
			$this->answers = $answers;
		}
	}

	/**
	 *
	 */
	public function getAnswers() {
		return $this->answers;
	}
	
	/**
	 * @Serializer\VirtualProperty
     * @Serializer\SerializedName("is_correct")
     * @Serializer\Groups({"solving","profile"})
	 */
	public function isCorrect() {
        
        foreach( $this->getCrossword()->getWords() as $word ) {
            
            $answer = $this->findAnswerByWordId($word->getId());
            if( !empty($answer) && $answer->getText() == $word->getText() ) {
                continue;
            }
            return false;
        }
                
		return true;
	}

    /**
	 *
	 */
	public function addAnswer( $answer ) {
		$this->answer[] = $answer;
	}   

    /**
     *
     * @param string $id 
     */
    protected function findAnswerByWordId( $id ) {
        
        foreach( $this->getAnswers() as $answer ) {
            if ( $answer->getWordId() == $id ) {
                return $answer;
            }
        }
        
        return null;
    } 

    /**
     * 
     */
    protected function updateAnswers( $answers ) {

		foreach ( $this->getAnswers() as $answerIndex => $existAnswer ) {
			$foundAnswer = null;
			foreach ( $answers as $key => $answer ) {
				if ( $answer->getWordId() == $existAnswer->getWordId() ) {
					$foundAnswer = $answer;
					unset($answers[$key]);
				}
			}
			
			if ( empty( $foundAnswer ) ) {
				$this->removeAnswer( $existAnswer );
				continue;
			}

			$this->updateAnswer( $answerIndex, $foundAnswer );
		}
		
		if ( count( $answers ) > 0 ) {
			foreach ( $answers as $answer ) {
				$this->addAnswer($answer);
			}
		}
    }
    
	/**
	 *
	 */
	private function removeAnswer( $answerToRemove ) {
		foreach ( $this->answers as $key => $answer ) {
			if ( $answerToRemove->getId() == $answer->getId() ) {
				unset( $this->answers[$key] );
				return true;
			}
		}
		return false;
	}
	
	/**
	 *
	 */
	private function updateAnswer( $index,  $answer ) {
		$existAnswer = $this->answers[$index];
		$existAnswer->setWordId( $answer->getWordId() );
		$existAnswer->setText( $answer->getText() );
		
		$this->answers[$index] = $existAnswer;
	}
}