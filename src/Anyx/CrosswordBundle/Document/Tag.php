<?php

namespace Anyx\CrosswordBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document(repositoryClass="Anyx\CrosswordBundle\Repository\TagRepository")
 */
class Tag
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $text;

    /**
     * @MongoDB\Int
     */
    protected $weight = 0;

    public function getId()
    {
        return $this->id;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    public function incWeight()
    {
        $this->weight++;
    }

    public function decWeight()
    {
        $this->weight--;
    }
}
