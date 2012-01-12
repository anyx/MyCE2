<?php
/**
 * 
 */
namespace Anyx\CrosswordBundle\Document;

/**
 * 
 */
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\SerializerBundle\Annotation\Accessor;
/**
 * 
 * @MongoDB\Document(repositoryClass="Anyx\CrosswordBundle\Repository\CrosswordRepository")
 */
class Crossword {

    /**
	 * 
     * @MongoDB\Id
     */
	protected $id;
	
	/**
	 * 
	 * @MongoDB\String
	 */
	protected $title;

	/**
	 * 
	 * @MongoDB\String
	 */
	protected $description;

	/**
	 * 
	 * @MongoDB\EmbedMany(targetDocument="Word")
	 */
	protected $words;
	
	/**
	 *
	 * @MongoDB\Boolean
	 */
	protected $public;

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
		
		$findWord = function( $id, $words = array() ) {
			foreach ( $words as $word ) {
				if ( $word->getId() == $id ) {
					return $word;
				}
			}
			return null;
		};
		
		foreach ( $this->getWords() as $wordIndex => $existWord ) {
			$existWord = $findWord( $existWord->getId(), $words );
			if ( empty( $existWord ) ) {
				$this->removeWord( $existWord );
				continue;
			}
			
			$this->updateWord( $wordIndex, $existWord );
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
	private function getWordById( $id ) {
		foreach ( $this->getWords() as $word ) {
			if ( $word->getId() == $id ) {
				return $word;
			}
		}
		return null;		
	}
	
	/**
	 *
	 */
	private function removeWord( $word ) {
		foreach ( $this->words as $key => $word ) {
			if ( $word->getId() == $id ) {
				unset( $this->words[$key] );
				return true;
			}
		}
		return false;
	}
	
	/**
	 *
	 */
	protected function updateWord( $index,  $word ) {
		$existWord = $this->words[$index];
		$existWord->setText( $word->getText() );
		$existWord->setDefinition( $word->getDefinition() );
		$existWord->setHorizontal( $word->getHorizontal() );
		$existWord->setPosition( $word->getPosition() );
		
		$this->words[$index] = $existWord;
	}
}
