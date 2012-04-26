<?php

namespace Anyx\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProfileController extends Controller {

    /**
     * @Route("/profile", name="user_profile")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

	public function createdAction() {

	}

	/**
     * @Route("/profile/solved-crosswords/{skip}", name="solved_crosswords", defaults={"skip" = 0})
     */
	public function solvedAction( $skip = 0) {

		$securityContext = $this->get('security.context');
		
		if ( !$securityContext->isGranted('ROLE_USER') ) {
			throw new AccessDeniedException;
		}
		
		$currentUser = $securityContext->getToken()->getUser();
		
		$crosswordRepository =$this->get('anyx.dm')->getRepository('Anyx\CrosswordBundle\Document\Solution');
		
		$solutions = $crosswordRepository->getUserSolutions( $currentUser, $skip );
		
		return new Response( $this->get('serializer')->serialize( $solutions->toArray(), 'json' ) );
	}
}
