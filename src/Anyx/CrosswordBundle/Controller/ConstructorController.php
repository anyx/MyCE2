<?php
/**
 * 
 */
namespace Anyx\CrosswordBundle\Controller;

/**
 * 
 */
use Symfony\Bundle\FrameworkBundle\Controller\Controller,
	Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\Request,
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
	 * @Template
	 */
	public function indexAction( Document\Crossword $crossword ) {
		return array(
			'crossword'	=> $crossword
		);
	}
	
	/**
	 *
	 * @Route("/{id}/save", name="constructor_save", options={"expose" = true})
	 * @ParamConverter("crossword", class="Anyx\CrosswordBundle\Document\Crossword")
	 * @Method({"POST"})
	 */
	public function saveAction( Document\Crossword $crossword, Request $reguest ) {
		
		$words = $reguest->get('words');
		
        if ( !empty( $words ) ) {
            $wordsDocuments = $this->get('anyx.document.factory')->createCollection( 'Word', $words, false );

            $crossword->updateWords( $wordsDocuments );

            $dm = $this->get('anyx.dm');

            $dm->persist( $crossword );

            $dm->flush();
        }
        
		return new Response(
                    $this->get('serializer')->serialize(
                            array(
                                'success'   => true,
                                'crossword' => $crossword,
                                'words'     => array_values($crossword->getWords()->toArray())
                            ),
                            'json'
                    )
        );
	}
}