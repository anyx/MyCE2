<?php

namespace Anyx\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

	public function solvedAction() {

	}
}
