<?php

namespace Anyx\SocialBundle\Authentication;

use Anyx\SocialBundle\Provider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Manager {
	
	/**
	 * 
	 */
	protected $providerFactory;

	/**
	 *
	 */
	public function __construct( Provider\Factory $factory ) {
		$this->providerFactory = $factory;
	}
	
	/**
	 *
	 * @return Anyx\SocialBundle\Provider\Factory 
	 */
	public function getProviderFactory() {
		return $this->providerFactory;
	}

	/**
	 *
	 * @param type $service
	 * @param Request $request 
	 */
	public function getAccessToken( $service, Request $request ) {

		$provider = $this->getProviderFactory()->getProvider( $service );

		if ( $request->get('code') == null ) {
			$response = new RedirectResponse( $provider->getAuthorizationUrl( $request ) );
			$response->send();
		}
		
		return $provider->getAccessToken( $request );
	}
	
	/**
	 *
	 * @param string $service
	 * @param string $accessToken 
	 */
	public function getUserData( $service, $accessToken ) {
		return $this->getProviderFactory()->getProvider( $service )->getUserData( $accessToken );
	}
}
