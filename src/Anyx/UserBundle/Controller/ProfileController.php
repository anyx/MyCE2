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
     * @Route("/profile", name="user_profile")
     * @Template()
     */
    public function indexAction()
    {
        /*
		$obj = new \JMS\SerializerBundle\Tests\Fixtures\ObjectWithVirtualProperties;
		$format = 'json';
		
        $serializer = $this->get('serializer');
        //$serializer->setGroups(array('xml-value'));
		//$s = $serializer->serialize($obj, $format );
		
		//var_dump( $obj, $s, $this->get('serializer')->deserialize( $s, get_class($obj), $format ) );
		var_dump( '|', $serializer->serialize( $obj, $format ) );
        */
		return array();
		
    }

	public function createdAction() {

	}

	/**
     * @Route("/profile/solved-crosswords/")
     */
	public function solvedAction( Request $request ) {

        $skip = (int) $request->get('skip', 0);
        
		$securityContext = $this->get('security.context');
		
		if ( !$securityContext->isGranted('ROLE_USER') ) {
			throw new AccessDeniedException;
		}
		
		$currentUser = $securityContext->getToken()->getUser();
		
		$solutionsRepository = $this->get('anyx.dm')->getRepository('Anyx\CrosswordBundle\Document\Solution');
		
		$solutions = $solutionsRepository->getUserSolutions( $currentUser, $skip );
		
		return new Response( $this->get('serializer')->serialize( array_values( $solutions->toArray() ), 'json' ) );
	}
}
