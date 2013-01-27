<?php

namespace Anyx\UserBundle\Controller;

use Anyx\SocialUserBundle\Controller\LoginController as BaseController;
use Anyx\SocialBundle\Authentication\UserDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Symfony\Component\Translation\Translator;


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
     */
    protected $translator;


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
     * @return \Symfony\Component\Translation\Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * 
     * @param \Symfony\Component\Translation\Translator $translator
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
    }

    /**
     * 
     * @param string $service
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function authAction($service, Request $request)
    {
        try {
            return parent::authAction($service, $request);
        } catch(UserDeniedException $exception) {
            $request->getSession()->setFlash(
                                'message',
                                $this->getTranslator()->trans('You regected authorization through'). ' ' . $service);
            return new RedirectResponse($this->getRouter()->generate('fos_user_security_login'));
        }
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
