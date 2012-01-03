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
 * @MongoDB\Document
 */
class Word {
	
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
	 * @MongoDB\int
	 */
	protected $x;

	/**
	 * @MongoDB\int
	 */
	protected $y;

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
		$this->horizontal = $horizontal;
	}

	/**
	 *
	 */
	public function getX() {
		return $this->x;
	}

	/**
	 *
	 */
	public function setX($x) {
		$this->x = $x;
	}

	/**
	 *
	 */
	public function getY() {
		return $this->y;
	}

	/**
	 *
	 */
	public function setY($y) {
		$this->y = $y;
	}
}
