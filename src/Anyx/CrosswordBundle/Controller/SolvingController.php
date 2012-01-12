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
}
