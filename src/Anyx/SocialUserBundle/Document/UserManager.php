<?php

namespace Anyx\SocialUserBundle\Document;


use Anyx\SocialUserBundle\User\SocialAccount;
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
		return $this->repository
				->createQueryBuilder()
				->field('enabled')->equals( true )
				->field('socialAccounts.accountId')->equals( $account->getAccountId() )
				->field('socialAccounts.serviceName')->equals( $account->getServiceName() )
				->getQuery()
				->getSingleResult()
		;		
	}
	
	/**
	 *
	 * @param SocialAccount $account 
	 */
	public function createUserFromAccount( SocialAccount $account ) {
		$user = $this->createUser();
		
		$user->addSocialAccount( $account );
		$user->setUsername( $account->getServiceName() . $account->getAccountId() . time() );
		
		return $user;
	}
	
	/**
	 *
	 * @param SocialAccount $account
	 * @return User 
	 */
	public function getAccountOwner( SocialAccount $account ) {
		
		$user = $this->findUserByAccount($account);
		if ( empty( $user ) ) {
			$user = $this->createUserFromAccount($account);
		}
		
		return $user;
	}
}
