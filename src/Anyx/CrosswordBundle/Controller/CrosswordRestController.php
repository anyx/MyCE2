<?php
/**
 * 
 */
namespace Anyx\CrosswordBundle\Controller;

/**
 * 
 */
use Symfony\Bundle\FrameworkBundle\Controller\Controller,
	// 	
	FOS\RestBundle\Controller\Annotations\Prefix,
	FOS\RestBundle\Controller\Annotations\NamePrefix,
	FOS\RestBundle\Controller\Annotations\View,
	FOS\RestBundle\View\RouteRedirectView,
	FOS\RestBundle\View\View AS FOSView,
	Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
	//	
	Anyx\CrosswordBundle\Document,

	//tmp
	Symfony\Component\HttpFoundation\Response
;

/**
 * @Prefix("crossword")
 * @NamePrefix("crossword_")
 */
class CrosswordRestController extends Controller {

	/**
	 * @View()
	 */
	public function listAction( $page, $limit = 25 ) {
		$dm = $this->get('dm');
		$crosswordRepository = $dm->getRepository('Anyx\CrosswordBundle\Document\Crossword');
		//return $crosswordRepository->getPublicCrosswords();
		//return new Response( 'sd' );
		return array( 'test' => 'asd' );
	}
}