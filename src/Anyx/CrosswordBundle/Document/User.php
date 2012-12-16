<?php

namespace Anyx\CrosswordBundle\Document;

use Anyx\SocialUserBundle\Document\User as BaseUser;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * 
 * @MongoDB\Document
 */
class User extends BaseUser
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\String
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