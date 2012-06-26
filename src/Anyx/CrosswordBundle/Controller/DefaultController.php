<?php

namespace Anyx\CrosswordBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request; 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Anyx\CrosswordBundle\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * 
 * 
 */
class DefaultController extends Controller {

	/**
	 * @Route("/", name="homepage")
	 * @Template
	 */
    public function indexAction( Request $request ) {
		
		$dm = $this->get('anyx.dm');
		$crosswordsRepository = $dm->getRepository('Anyx\CrosswordBundle\Document\Crossword');
		
		return array(
			'crosswords' => array(
				'popular'	=> $crosswordsRepository->getPopular(),
				'new'		=> $crosswordsRepository->getNew(),
			), 
            'baseUrl'   => $request->getScheme() . '://' . $request->getHttpHost()
		);
	}
	
	/**
	 * 
	 * @Template("AnyxCrosswordBundle:Crossword:list.html.twig")
	 */
	
	public function testAction() {
        return $this->render('AnyxCrosswordBundle:Default:test.html.twig', array());
	}
	
	/**
	 * @Template
	 */
	public function listAuthServicesAction() {
		
		$servicesCodes = $this->get('anyx_social.provider.factory')->getServices();
		$services = array();
		
		$router = $this->get('router');
		
		foreach( $servicesCodes as $service ) {
			$services[$service] = $router->generate(
									'anyx_social_auth',
									array(
										'service' => $service
									)
			);
		}
		
		return array(
			'services' => $services
		);
	}	
}
