<?php

namespace Anyx\CrosswordBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Anyx\CrosswordBundle\Document;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
;

/**
 * 
 */
class ListsController extends Controller {

	/**
	 * @Route("/new", name="popular_crosswords")
	 * @Template
	 */
    public function listNewAction() {
        /*
        $documentManager = $this->get('anyx.dm');
        $solutionRepository = $documentManager->getRepository('Anyx\CrosswordBundle\Document\Solution');
        */
        $index = $this->get('foq_elastica.index.website.crossword');
        $result = $index->search('Первый');
        
        
        var_dump( $result->count() );
        
        foreach( $result as $t ) {
            var_dump( $t );
        }
        
        return new Response('t');
	}
    
    /**
     * 
     */
    public function searchAction( Request $request ) {
        
        $term = $request->get('term', '');
        if ( !empty($term) ) {
            $result = $this->get('foq_elastica.index.website.crossword')->search( $term );
            
            if ( $result->count() > 0 ) {
                //$crosswords = 
            }
        }
        
    }
}
