<?php
/**
 * 
 */
namespace Anyx\CrosswordBundle\Document;

/**
 * 
 */
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * 
 * @MongoDB\EmbeddedDocument
 */
class Word {

    /**
	 * 
     * @MongoDB\Id
     */
	protected $id;
	
	/**
	 * @MongoDB\String
	 */
	protected $text;

	/**
	 * @MongoDB\String
	 */
	protected $definition;

	/**
	 * @MongoDB\Boolean
	 */
	protected $horizontal;
	
	/**
	 * @MongoDB\Field(type="point")
	 */
	protected $position;
	
	/**
     * Get id
     *
     * @return id $id
     */
    public function getId() {
        return $this->id;
    }
	
	/**
	 *
	 * @param string $id 
	 */
	public function setId( $id ) {
		$this->id = $id;
	}

	/**
	 *
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 *
	 */
	public function setText($text) {
		$this->text = $text;
	}

	/**
	 *
	 */
	public function getDefinition() {
		return $this->definition;
	}

	/**
	 *
	 */
	public function setDefinition($definition) {
		$this->definition = $definition;
	}

	/**
	 *
	 */
	public function getHorizontal() {
		return $this->horizontal;
	}

	/**
	 *
	 */
	public function setHorizontal($horizontal) {
		if ( $horizontal == 'false' ) {
			$horizontal = false;
		}
		$this->horizontal = $horizontal;
	}

	/**
	 *
	 */
	public function setPosition( $position ) {
		if ( !array_key_exists('x', $position) || !array_key_exists('y', $position) ) {
			throw new \InvalidArgumentException( 'Position must be array with keys x and y' );
		}
		
		$this->position = $position;
	}
	
	/**
	 * 
	 */
	public function getPosition() {
		return $this->position;
	}
}
