<?php
/**
 * 
 */
namespace Anyx\CrosswordBundle\Document;

/**
 * 
 */
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

use JMS\SerializerBundle\Annotation as Serializer;

/**
 * 
 * @MongoDB\Document(repositoryClass="Anyx\CrosswordBundle\Repository\CrosswordRepository")
 */
class Crossword {

    /**
     * @MongoDB\Id
     */
	protected $id;
	
	/**
	 * @MongoDB\String
	 */
	protected $title;

	/**
	 * @MongoDB\String
     * @Serializer\Accessor(getter="getTruncatedDescription")
	 */
	protected $description;

	/**
	 * @MongoDB\EmbedMany(targetDocument="Word")
	 * @Serializer\Exclude
	 */
	protected $words;
	
	/**
	 * @MongoDB\Boolean
	 */
	protected $public;

	/**
	 * @MongoDB\Int
	 */
	protected $countSolving = 0;
	
	/**
	 * @MongoDB\Date
     * @Serializer\Type("DateTime",format="Y")
	 */
	protected $createdAt;

	/**
	 * @MongoDB\Date
     * @Serializer\Type("DatdeTime")
	 */
	protected $updatedAt;

	/**
	 * @MongoDB\ReferenceOne(targetDocument="User")
	 */
	protected $owner;

    /**
     *
     * @return Anyx\CrosswordBundle\Document\User
     */
    public function getOwner() {
        return $this->owner;
    }

    /**
     *
     * @param Document\User $owner 
     */
    public function setOwner( User $owner) {
        $this->owner = $owner;
    }

    /**
     *
     * @param Document\User $owner 
     */
    public function hasOwner( User $user ) {
        return !empty($this->owner) && $this->owner->getId() == $user->getId();
    }
    
    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     *
     * @return DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     *
     * @return DateTime
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }
        
    public function getTruncatedDescription( $length = 100, $separator = '...' ) {
        
        $description = $this->description;
        
        if (mb_strlen($description) > $length) {
            if (false !== ($breakpoint = mb_strpos($description, ' ', $length ))) {
                $length = $breakpoint;
            }

            return mb_substr($description, 0, $length) . $separator;
        }

        return htmlspecialchars( $description );        
    }

    /**
	 *
	 * @return bool
	 */
	public function isPublic() {
		return $this->public;
	}

	/**
	 *
	 * @param bool $public 
	 */
	public function setPublic($public) {
		$this->public = $public;
	}
	
	/**
	 *
	 */
	public function getWords() {
		return $this->words;
	}

	/**
	 * 
	 */
	public function getWordsForSolving() {
		
		$result = array();
		$words = $this->getWords()->toArray();
		
		if ( empty($words) ) {
			return array();
		}
		
		foreach ( $words as $key => $word ) {
			$result[] = array(
				'id'			=> $word->getId(),
				'number'		=> $key,
				'length'		=> mb_strlen( $word->getText() ),
				'horizontal'	=> $word->getHorizontal(),
				'definition'	=> $word->getDefinition(),
				'position'		=> $word->getPosition()
			);
		}
		
		return $result;
	}
	
	/**
	 *
	 */
	public function setWords($words) {
		$this->words = $words;
	}
	
	/**
	 *
	 */
	public function addWord( $word ) {
		$this->words[] = $word;
	}

	/**
	 *
	 * @param array $words 
	 */
	public function updateWords( $words ) {
		
		foreach ( $this->getWords() as $wordIndex => $existWord ) {
			$foundWord = null;
			foreach ( $words as $key => $word ) {
				if ( $word->getId() == $existWord->getId() ) {
					$foundWord = $word;
					unset($words[$key]);
				}
			}
			
			if ( empty( $foundWord ) ) {
				$this->removeWord( $existWord );
				continue;
			}

			$this->updateWord( $wordIndex, $foundWord );
		}
		
		if ( count( $words ) > 0 ) {
			foreach ( $words as $word ) {
				$this->addWord($word);
			}
		}
	}

	/**
	 * 
	 */
	public function hasWords() {
		return count( $this->words ) > 0;
	}
	
	/**
	 *
	 */
	public function getCountSolving() {
		return $this->countSolving;
	}

	/**
	 *
	 */
	public function incCountSolving() {
		return $this->countSolving++;
	}
	
	/**
	 *
	 */
	private function removeWord( $wordToRemove ) {
		foreach ( $this->words as $key => $word ) {
			if ( $wordToRemove->getId() == $word->getId() ) {
				unset( $this->words[$key] );
				return true;
			}
		}
		return false;
	}
	
	/**
	 *
	 */
	private function updateWord( $index,  $word ) {
		$existWord = $this->words[$index];
		$existWord->setText( $word->getText() );
		$existWord->setDefinition( $word->getDefinition() );
		$existWord->setHorizontal( $word->getHorizontal() );
		$existWord->setPosition( $word->getPosition() );
		
		$this->words[$index] = $existWord;
	}
	
	/**
	 * Events
	 */

	/**
	 *
	 * @MongoDB\PostLoad
	 */
	public function sortWords() {
		$words = $this->getWords();

		if ( empty( $words ) ) {
			return false;
		}
		
		$cmpFunction = function( $word1, $word2 ) {
			$position1 = $word1->getPosition();
			$position2 = $word1->getPosition();

			if ( $position1['y'] > $position2['y'] ) {
				return -1;
			} elseif( $position1['y'] == $position2['y'] ) {
				return $position1['x'] > $position2['x'] ? -1 : 1; 
			} else {
				return 1;
			}
		};
		
		if (is_object( $words ) ) {
			
			$wordsArray = $words->toArray();

			usort($wordsArray, $cmpFunction );
			
			foreach ( $wordsArray as $key => $word ) {
				$words[$key] = $word;
			}
		} else {
			usort($words, $cmpFunction );
		}
		
		$this->setWords( $words );
	}
	
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
}
