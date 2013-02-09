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
 * @MongoDB\Document(repositoryClass="Anyx\CrosswordBundle\Repository\CrosswordEstimateRepository")
 */
class CrosswordEstimate
{
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
	 * @MongoDB\Int
	 */
	protected $estimate;

    public function getId()
    {
        return $this->id;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getCrossword()
    {
        return $this->crossword;
    }

    public function setCrossword(Crossword $crossword)
    {
        $this->crossword = $crossword;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getEstimate()
    {
        return $this->estimate;
    }

    public function setEstimate($estimate)
    {
        $this->estimate = $estimate;
    }
    
    /**
     * @MongoDB\PrePersist
     */
    public function setCreatedAt()
    {
        $this->createdAt = $this->updatedAt = new \DateTime();
    }
}
