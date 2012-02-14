<?php

namespace Anyx\CrosswordBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
	Symfony\Component\HttpFoundation\Response,
	Anyx\CrosswordBundle\Document,

	FOS\RestBundle\Controller\Annotations\View,		
		
	Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\Method,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter
;

/**
 * 
 * 
 */
class SolvingController extends Controller {

	/**
	 * @ParamConverter("crossword", class="Anyx\CrosswordBundle\Document\Crossword")
	 * @Route("/crossword/{id}/solve", name="crossword_solve")
	 * @View
	 */
    public function indexAction( Document\Crossword $crossword ) {
		return array(
			'crossword' => $crossword
		);
	}

	/**
	 * @ParamConverter("crossword", class="Anyx\CrosswordBundle\Document\Crossword")
	 * @Route("/crossword/{id}/solve/save", name="crossword_save_solution", options={"expose" = true})
	 * @Method({"POST"})
	 */
	public function saveAction( Document\Crossword $crossword ) {

		$solutionData = $this->get('request')->get('solution');

		$documentManager = $this->get('anyx.dm');

		$factory = $this->get('anyx.document.factory');
		$user = $this->get('security.context')->getToken()->getUser();

		$answers = array();
		foreach( $crossword->getWords() as $word ) {
			if ( array_key_exists( $word->getId(), $solutionData ) ) {
				$answers[]	= $factory->create('Answer', array(
					'word'		=> $word,
					'answer'    => $solutionData[$word->getId()]
				), false);
			}
		}

		$solution = $documentManager->getRepository('Anyx\CrosswordBundle\Document\Solution')->getUserSolution( $user, $crossword );

		if( empty( $solution ) ) {
			$solution = $factory->create('Anyx\CrosswordBundle\Document\Solution', array(
				'user'			=> $user,
				'crossword' 	=> $crossword
			));
		}

		$solution->setAnswers( $answers );

		$documentManager->flush();

		return new Response(json_encode(array(
			'success'	=> true
		)));
	}
}