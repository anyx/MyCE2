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
	 * @Route("/{id}", name="constructor")
	 * @ParamConverter("crossword", class="Anyx\CrosswordBundle\Document\Crossword")
	 */
	public function indexAction( Document\Crossword $crossword ) {

		$crossword = $this->get( 'anyx.document.factory')->create(
				'Crossword',
				array(
					'title'	=> 'tit'
				)
		);

		var_dump( $crossword );
		
		return $this->render('AnyxCrosswordBundle:Constructor:index.html.twig', array(
			'crossword'	=> $crossword
		));
	}
	
	/**
	 *
	 * @Route("/{id}/save", name="constructor_save")
	 * @ParamConverter("crossword", class="Anyx\CrosswordBundle\Document\Crossword")
	 * @Method({"POST"})
	 */
	public function saveAction( Document\Crossword $crossword, $words ) {
		
	}
}