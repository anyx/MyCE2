<?php

namespace Anyx\SocialBundle\Document;


use Anyx\SocialBundle\User\SocialAccount;
use FOS\UserBundle\Document\UserManager as BaseUserManager;


/**
 * Description of UserManager
 *
 * @author aleks
 */
class UserManager extends BaseUserManager {
	
	/**
	 *
	 * @param SocialAccount $account 
	 */
	public function findUserByAccount( SocialAccount $account ) {
		
	}
	
	/**
	 *
	 * @param SocialAccount $account 
	 */
	public function createUserFromAccount( SocialAccount $account ) {
		$user = $this->createUser();
		
		$user->addSocialAccount( $account );
		$user->setUsername( $account->getUserName() );
		return $user;
	}
}
