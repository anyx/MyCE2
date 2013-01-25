<?php

namespace Anyx\UserBundle\Controller;

use Anyx\SocialUserBundle\Controller\LoginController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * @Route(service="anyx_social_user.controller.login") 
 */
class SocialLoginController extends BaseController
{
    /**
     * 
     */
    protected $router;

    /**
     * 
     * @return \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * 
     * @param \Symfony\Bundle\FrameworkBundle\Routing\Router $router
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;
    }

    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return string
     */
    protected function getSuccessUrl(Request $request)
    {
        return $this->getRouter()->generate('fos_user_profile_show');
    }
}
