<?php

namespace Anyx\SocialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/profile/social")
     * @Template()
     */
    public function indexAction()
    {
		return array('name' => 'sd');
    }
	

    /**
     * @Route("/social/auth/{service}",  name="social_auth")
     */
	public function authAction( $service ) {
		
		$manager = $this->get('anyx_social.account.manager');
		$manager->getProviderFactory()->setProvidersOption( 'redirect_uri', $this->getRedirectUri( $service ) );
		
		$accessToken = $manager->getAccessToken( $service, $this->get('request') );
		//var_dump( 'token', $accessToken );

		$userData = $manager->getUserData( $service, $accessToken );
		//var_dump( 'data', $userData );

		$accountFactory = $this->get('anyx_social.account.factory');
		
		$account = $accountFactory->createAccount( $service, $userData );
		var_dump('account', $account );

		$userManager = $this->get('fos_user.user_manager');

		$user = $userManager->findUserByAccount( $account );
	
		if ( empty( $user ) ) {
			$user = $userManager->createUserFromAccount( $account );
		}
		
		var_dump('user', $user);
		
		$userManager->updateUser( $user );
		
		return;

		if ( $authorized ) {
			//link account
		} else {
			if ( !empty( $user ) ) {
				//auth found user
			} else {
				//create user
			}
		}
		
		
		
	//	$userData = $provider->getUserData( $accessToken );
	//	var_dump('user data', $userData);
		return new Response('sad');
	}
	
	/**
	 *
	 * @return tstring
	 */
	protected function getRedirectUri( $service ) {

		$request = $this->get('request');
		$router = $this->get('router');
		$route = $request->get('_route');
		
		$path = $router->generate( $route, array(
			'service'	=> $service
		));
		
		return $request->getScheme() . '://' . $request->getHttpHost()  . $path;
	}
}
