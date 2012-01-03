<?php

namespace Anyx\CrosswordBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
	Symfony\Component\HttpFoundation\Response,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
	FOS\RestBundle\Controller\Annotations\View,
	Anyx\CrosswordBundle\Document;

/**
 * 
 * 
 */
class DefaultController extends Controller {
	
	/**
	 * @fix @Template & @View conflict
	 */
	
	/**
	 * 
	 * @View("AnyxCrosswordBundle:Crossword:list.html.twig")
	 */
	public function mongoAction() {
		return array('asd' => 'sad');
	}
	
    public function indexAction($name) {
        return $this->render('AnyxCrosswordBundle:Default:index.html.twig', array('name' => $name));
    }
	
	public function testAction() {
        return $this->render('AnyxCrosswordBundle:Default:test.html.twig', array());
	}
	
	public function mongo1Action() {

		$dm = $this->get('dm');
		$crossword = new Document\Crossword();

		$crossword->setTitle('TestCrossword');
		$crossword->setDescription('Description');
		$crossword->setPublic( true );
		
				
		$dm->persist( $crossword );
		$dm->flush();
		
		return new Response( 'create' );
	}
}
