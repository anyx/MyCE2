<?php

namespace Anyx\SocialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @Route("",service="anyx_social.controller.login") 
 */
class LoginController extends Controller
{
	/**
	 * @var type 
	 */
	protected $request;

	/**
	 * @var Anyx\SocialBundle\Authentication\Manager
	 */
	protected $authenticationManager;

	/**
	 *
	 * @var AccountFactory;
	 */
	protected $accountFactory;
	
	/**
	 *
	 */
	protected $securityContext;

	/**
	 *
	 * @param Authentication\Manager $authenticationManager
	 * @param type $userManager
	 * @param type $securityContext 
	 */
	function __construct(	Authentication\Manager $authenticationManager,
							AccountFactory $accountFactory,
							$securityContext ) {
		
		$this->authenticationManager = $authenticationManager;
		$this->userManager = $userManager;
		$this->accountFactory = $accountFactory;
		$this->securityContext = $securityContext;
	}

	/**
	 *
	 * @return Authentication\Manager
	 */
	public function getAuthenticationManager() {
		return $this->authenticationManager;
	}

	/**
	 *
	 * @return type 
	 */
	public function getSecurityContext() {
		return $this->securityContext;
	}

	/**
	 *
	 * @return AccountFactory
	 */
	public function getAccountFactory() {
		return $this->accountFactory;
	}

	/**
	 *
	 * @return UserManager
	 */
	public function getUserManager() {
		return $this->userManager;
	}

	/**
	 *
	 * @return Request
	 */
	public function getRequest() {
		return $this->request;
	}

	/**
	 *
	 * @param Request $request 
	 */
	public function setRequest( Request $request) {
		$this->request = $request;
	}

    /**
     * @Route("/social/auth/{service}",  name="anyx_social_auth")
     */
	public function authAction( $service, Request $request ) {
		
		$manager = $this->getAuthenticationManager();

		$manager->getProviderFactory()->setProvidersOption( 'redirect_uri', $this->getRedirectUri( $request ) );
		
		$accessToken = $manager->getAccessToken( $service, $request );

		$userData = $manager->getUserData( $service, $accessToken );

		$accountFactory = $this->getAccountFactory();
		
		$account = $accountFactory->createAccount( $service, $userData );

		$userManager = $this->getUserManager();

		if ( $this->getSecurityContext()->isGranted('ROLE_USER') ) {
			//link account
			$accountOwner = $this->getSecurityContext()->getToken()->getUser();
			$accountOwner->addAccount( $account );
			
		} else {
			
			$accountOwner = $userManager->getAccountOwner( $account );
			
			$token = new UsernamePasswordToken(
					$accountOwner,
					null,
					'main',//todo!!!
					$accountOwner->getRoles()
			);
			$this->getSecurityContext()->setToken($token);
		}
		
		$userManager->updateUser( $accountOwner );
		
		$backurl = $request->get( 'backurl' );
		if ( empty( $backurl ) ) {
			$backurl = '/';
		}
		
		return new RedirectResponse( $request->getBaseUrl() . $backurl );
	}
	
	/**
	 * @todo refacator this shit
	 * 
	 * @return string
	 */
	protected function getRedirectUri( Request $request ) {

		$path = $request->getUri();
		$path = str_replace( $request->getQueryString(), '', $request->getUri() );
		if (strpos($path, '?') == strlen($path) - 1 ) {
			$path = substr($path, 0, -1);
		}
		
		return $path;
	}
}