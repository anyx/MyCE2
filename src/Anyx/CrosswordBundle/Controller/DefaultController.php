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
				'popular' => $crosswordsRepository->getPopular()
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
}
