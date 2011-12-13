<?php

namespace Anyx\CrosswordBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Anyx\CrosswordBundle\Document;

class DefaultController extends Controller
{
    
    public function indexAction($name)
    {
        return $this->render('AnyxCrosswordBundle:Default:index.html.twig', array('name' => $name));
    }
	
	public function testAction() {
		
		$crossword = new Document\Crossword();
		/*
		$crossword->setTitle('TestCrossword');
		
		$dm = $this->get('doctrine.odm.mongodb.document_manager');
		$dm->persist($crossword);
		$dm->flush();
		*/
		//var_dump( $dm );
        return $this->render('AnyxCrosswordBundle:Default:test.html.twig', array());
	}
}
