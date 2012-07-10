<?php

namespace Anyx\CrosswordBundle\Controller;

use Anyx\CrosswordBundle\Document;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request; 

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
		
		$crosswordsRepository = $this->get( 'anyx.dm' )->getRepository( 'Anyx\CrosswordBundle\Document\Crossword');
        $newCrosswords = $this->getPaginator( $crosswordsRepository->getNewCrosswordsQueryBuilder() );
        $newCrosswords->setMaxPerPage(5);
        
        $popularCrosswords = $this->getPaginator( $crosswordsRepository->getNewCrosswordsQueryBuilder() );
        $popularCrosswords->setMaxPerPage(5);

        return array(
			'crosswords' => array(
				'popular'	=> $popularCrosswords,
				'new'		=> $newCrosswords,
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
