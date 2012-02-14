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
 * @MongoDB\EmbeddedDocument
 */
class Answer {

	/**
	 * @MongoDB\Id
	 */
	protected $id;

	/**
	 * @MongoDB\ReferenceOne(targetDocument="Word")
	 */
	protected $word;

	/**
	 * @MongoDB\String
	 */
	protected $answer;

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param $answer
	 */
	public function setAnswer($answer) {
		$this->answer = $answer;
	}

	/**
	 * @return mixed
	 */
	public function getAnswer() {
		return $this->answer;
	}

	/**
	 * @param Word $word
	 */
	public function setWord( Word $word) {
		$this->word = $word;
	}

	/**
	 * @return Word
	 */
	public function getWord() {
		return $this->word;
	}
}