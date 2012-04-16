<?php

namespace Anyx\SocialUserBundle\Document;

use Anyx\SocialUserBundle\User\SocialAccount;
use FOS\UserBundle\Document\User as BaseUser;
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
	 * @MongoDB\EmbedMany(targetDocument="Anyx\SocialUserBundle\User\SocialAccount")
	 */
	protected $socialAccounts;

	/**
	 *
	 */
	public function getSocialAccounts() {
		return $this->socialAccounts;
	}

	/**
	 *
	 */
	public function setSocialAccounts($socialAccounts) {
		$this->socialAccounts = $socialAccounts;
	}

	/**
	 *
	 * @param SocialAccount $account 
	 */
	public function addSocialAccount( SocialAccount $account ) {
		$this->socialAccounts[] = $account;
	}
}