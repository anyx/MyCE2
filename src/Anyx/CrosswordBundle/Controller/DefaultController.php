<?php

namespace Anyx\CrosswordBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
	Symfony\Component\HttpFoundation\Response,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
	Anyx\CrosswordBundle\Document,
		
	Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter
;

/**
 * 
 * 
 */
class DefaultController extends Controller {

	/**
	 * @Route("/", name="homepage")
	 * @Template
	 */
    public function indexAction() {
		
		$dm = $this->get('anyx.dm');
		$crosswordsRepository = $dm->getRepository('Anyx\CrosswordBundle\Document\Crossword');
		
		return array(
			'crosswords' => array(
				'popular'	=> $crosswordsRepository->getPopular(),
				'new'		=> $crosswordsRepository->getNew()
			) 
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
