<?php

namespace Anyx\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Anyx\UserBundle\Virtual;

class ProfileController extends Controller {

    /**
     * @Route("/profile/", name="user_profile")
     * @Template()
     */
    public function indexAction()
    {
        /*
        $skip = 0;
        $securityContext = $this->get('security.context');
		$currentUser = $securityContext->getToken()->getUser();
		
		$crosswordsRepository = $this->get('anyx.dm')->getRepository('Anyx\CrosswordBundle\Document\Crossword');
		
		$crosswords = $crosswordsRepository->getUserCrosswords( $currentUser, 20, $skip );
        var_dump($crosswords);
        */
        
        /*
		$obj = new \Anyx\CrosswordBundle\Document\ObjectWithVirtualProperties;
		$format = 'xml';
		
        $serializer = $this->get('serializer');
        $serializer->setGroups(array('versions'));
        $serializer->setVersion(8);
        //$serializer->setGroups(array('xml-value'));
		//$s = $serializer->serialize($obj, $format );
		
		//var_dump( $obj, $s, $this->get('serializer')->deserialize( $s, get_class($obj), $format ) );
		var_dump( '|', $serializer->serialize( $obj, $format ) );
        */
        return array();
		
    }

    /**
     * @Route("/profile/created-crosswords/") 
     */
	public function createdAction( Request $request ) {

        $skip = (int) $request->get('skip', 0);
        
		$securityContext = $this->get('security.context');
		
		if ( !$securityContext->isGranted('ROLE_USER') ) {
			throw new AccessDeniedException;
		}
		
		$currentUser = $securityContext->getToken()->getUser();
		
		$crosswordsRepository = $this->get('anyx.dm')->getRepository('Anyx\CrosswordBundle\Document\Crossword');
		
		$crosswords = $crosswordsRepository->getUserCrosswords( $currentUser, 20, $skip );
		
		return new Response( $this->get('serializer')->serialize( array_values( $crosswords->toArray() ), 'json' ) );
	}

	/**
     * @Route("/profile/solved-crosswords/")
     */
	public function solvedAction( Request $request ) {

        return '';
        $skip = (int) $request->get('skip', 0);
        
		$securityContext = $this->get('security.context');
		
		if ( !$securityContext->isGranted('ROLE_USER') ) {
			throw new AccessDeniedException;
		}
		
		$currentUser = $securityContext->getToken()->getUser();
		
		$solutionsRepository = $this->get('anyx.dm')->getRepository('Anyx\CrosswordBundle\Document\Solution');
		
		$solutions = $solutionsRepository->getUserSolutions( $currentUser, 20, $skip );
		
		return new Response( $this->get('serializer')->serialize( array_values( $solutions->toArray() ), 'json' ) );
	}
    
	/**
     * @Route("/profile/remove-crossword/{id}", name="remove_crossword", options={"expose" = true})
     */
    public function removeCrosswordAction( $id ) {
        
        $result = false;
        $message = '';
       
        $dm = $this->get('anyx.dm');
        
        $currentUser = $this->get('security.context')->getToken()->getUser();
        
		$crosswordsRepository = $dm->getRepository('Anyx\CrosswordBundle\Document\Crossword');
		
		$crossword = $crosswordsRepository->getUserCrossword( $currentUser, $id);
        
        if ( empty($crossword) ) {
            $message = $this->get('translator')->trans('Crossword not found');
        } else {
            $dm->remove($crossword);
            $dm->flush();
            $result = true;
        }
        
        return new Response(json_encode(array(
            'success'    => $result,
            'message'    => $message
        )));
    }
}
