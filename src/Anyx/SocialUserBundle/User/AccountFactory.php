<?php

namespace Anyx\SocialUserBundle\User;

/**
 * Description of AccountFactory
 *
 */
class AccountFactory {

	/**
	 *
	 * @var type 
	 */
	protected $accountClass = 'Anyx\SocialUserBundle\User\SocialAccount';

	/**
	 *
	 * @var array
	 */
	protected $accountsMap;

	/**
	 *
	 * @param array $accountsMap 
	 */
	function __construct( array $accountsMap ) {
		$this->accountsMap = $accountsMap;
	}

	/**
	 *
	 * @return Anyx\SocialBundle\User\SocialAccount
	 */
	public function getAccountClass() {
		return $this->accountClass;
	}

	/**
	 *
	 * @param string $accountClass 
	 */
	public function setAccountClass($accountClass) {
		$this->accountClass = $accountClass;
	}
		
	/**
	 *
	 * @param string $service
	 * @param array $userData 
	 */
	public function createAccount( $service, $userData ) {
		$class = $this->getAccountClass();
		$account = new $class;
		
		$account->setAccountId( $this->findFieldValue( 'accountId', $service, $userData ) );
		$account->setServiceName( $service );
		$account->setAccountData( $userData );
		$account->setUserName( $this->findFieldValue( 'userName', $service, $userData ) );
		
		return $account;
	}
	
	/**
	 * 
	 */
	protected function findFieldValue( $field, $service, $userData ) {

		$map = $this->getAccountMap( $service );
		$fieldPath = $map[$field];
		
		foreach( explode( '.', $fieldPath ) as $key ) {
			if (!array_key_exists( $key, $userData ) ) {
				throw new \RuntimeException( "Key '$key' not found in user data" );
			}
			
			$userData = $userData[$key];
		}
		
		if ( !is_string( $userData ) ) {
			throw new \RuntimeException( "Value must be string" );
		}
		
		return $userData;
	}
	
	/**
	 *
	 * @param string $service
	 * @return array
	 */
	protected function getAccountMap( $service ) {
		if ( !array_key_exists($service, $this->accountsMap) ) {
			throw new \InvalidArgumentException( "Accont fields map not found for service '$service' " );
		} 
		return $this->accountsMap[$service];
	}
}
