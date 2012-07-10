<?php
/**
 * 
 */
namespace Anyx\PageBundle\Document;

/**
 * 
 */
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * 
 * @MongoDB\Document(repositoryClass="Anyx\PageBundle\Repository\PageRepository")
 */
class Page {

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
	 */
	protected $slug;
    
	/**
	 * @MongoDB\String
	 */
	protected $text;

	/**
	 * @MongoDB\String
	 */
	protected $keywords;

    /**
	 * @MongoDB\String
	 */
	protected $description;
	
	/**
	 * @MongoDB\Boolean
	 */
	protected $public;

	/**
	 * @MongoDB\Date
	 */
	protected $createdAt;

	/**
	 * @MongoDB\Date
	 */
	protected $updatedAt;

	/**
	 * @MongoDB\ReferenceOne(targetDocument="Anyx\CrosswordBundle\Document\User")
	 */
	protected $owner;

    /**
	 *
	 * @return bool
	 */
	public function isPublic() {
		return $this->public;
	}

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }

    public function getText() {
        return $this->text;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function getKeywords() {
        return $this->keywords;
    }

    public function setKeywords($keywords) {
        $this->keywords = $keywords;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getPublic() {
        return $this->public;
    }

    public function setPublic($public) {
        $this->public = $public;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function setOwner($owner) {
        $this->owner = $owner;
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
}
