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
 * @MongoDB\Document(repositoryClass="Anyx\CrosswordBundle\Repository\SolutionRepository")
 */
class Solution {

	/**
	 * @MongoDB\Id
	 */
	protected $id;

	/**
	 * @MongoDB\ReferenceOne(targetDocument="User")
	 */
	protected $user;

	/**
	 * @MongoDB\ReferenceOne(targetDocument="Crossword")
	 */
	protected $crossword;

	/**
	 * @MongoDB\Date
	 */
	protected $createdAt;

	/**
	 * @MongoDB\Date
	 */
	protected $updatedAt;

	/**
	 * @MongoDB\EmbedMany(targetDocument="Answer")
	 */
	protected $answers;

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
}