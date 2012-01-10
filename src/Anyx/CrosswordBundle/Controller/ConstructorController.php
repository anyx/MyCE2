<?php
/**
 * 
 */
namespace Anyx\CrosswordBundle\Controller;

/**
 * 
 */
use Symfony\Bundle\FrameworkBundle\Controller\Controller,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter,

	//
	Anyx\CrosswordBundle\Document
;

/**
 * 
 * @Route("/constructor")
 */
class ConstructorController extends Controller {

	/**
	 * 
	 * @Route("/{id}", name="constructor", options={"expose" = true})
	 * @ParamConverter("crossword", class="Anyx\CrosswordBundle\Document\Crossword")
	 */
	public function indexAction( Document\Crossword $crossword ) {
		return $this->render('AnyxCrosswordBundle:Constructor:index.html.twig', array(
			'crossword'	=> $crossword
		));
	}
	
	/**
	 *
	 * @Route("/{id}/save", name="constructor_save", options={"expose" = true})
	 * @ParamConverter("crossword", class="Anyx\CrosswordBundle\Document\Crossword")
	 * @Method({"POST"})
	 */
	public function saveAction( Document\Crossword $crossword ) {
		
		$words = $this->get('request')->get('words');
		
		$wordsDocuments = $this->get('anyx.document.factory')->createCollection( 'Word', $words );
		
		var_dump( $wordsDocuments );
		
		$crossword->setWords($wordsDocuments);
		
		$this->get('anyx.dm')->flush();
	}
}