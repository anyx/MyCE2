<?php

namespace Anyx\CrosswordBundle\Document;

use Anyx\SocialUserBundle\Document\User as BaseUser;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use JMS\Serializer\Annotation as Serializer;

/**
 * 
 * @MongoDB\Document
 */
class User extends BaseUser
{
    /**
     * @MongoDB\Id(strategy="auto")
     * @Serializer\Groups({"statistic"})
     */
    protected $id;

    /**
     * @MongoDB\String
     * @Serializer\Groups({"statistic"})
     */
    protected $name = '';

    /**
     * 
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * 
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}