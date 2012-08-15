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
 * @MongoDB\EmbeddedDocument
 */
class Answer {

	/**
	 * @MongoDB\Id
     * @Serializer\Groups({"solving"})
	 */
	protected $id;

	/**
	 * @MongoDB\String
     * @Serializer\Groups({"solving"})
	 */
	protected $wordId;

	/**
	 * @MongoDB\String
     * @Serializer\Groups({"solving"})
	 */
	protected $text;

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param $text
	 */
	public function setText($text) {
		$this->text = $text;
	}

	/**
	 * @return mixed
	 */
	public function getText() {
		return $this->text;
	}

    /**
     * 
     */
    public function getWordId() {
        return $this->wordId;
    }
    
    /**
     * 
     */
    public function setWordId( $wordId ) {
        $this->wordId = $wordId;
    }    
}