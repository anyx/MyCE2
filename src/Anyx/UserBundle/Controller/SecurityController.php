<?php

namespace Anyx\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;


class SecurityController extends BaseController
{
    /**
     * 
     * @param \Anyx\UserBundle\Controller\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginAction(Request $request)
    {
        if ($this->container->get('security.context')->isGranted('ROLE_USER')) {
            return new RedirectResponse($this->container->get('router')->generate('fos_user_profile_show'));
        }

        return parent::loginAction($request);
    }
}
