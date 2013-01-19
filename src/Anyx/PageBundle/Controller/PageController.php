<?php

namespace Anyx\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception as HttpException;

class PageController extends Controller
{
    /**
     * @Route("/page/{slug}", name="page")
     * @Template
     */
    public function indexAction($slug)
    {
        $template = ':Pages:'.$slug.'.html.twig';
        if ($this->get('templating')->exists($template)) {
            return $this->render($template);
        }
        
        $page = $this->get('anyx.dm')->getRepository('Anyx\PageBundle\Document\Page')->getPublicPageBySlug($slug);

        if ( empty( $page ) ) {
            throw new  HttpException\NotFoundHttpException('Page not found');
        }
        return array('page' => $page);
    }
}
