<?php

namespace Anyx\CrosswordBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\Container;


class KernelExceptionListener {

    /**
     * @var array
     */
    private $recipients = array();

    private $container;


    public function __construct( array $recipients, Container $container ) {
        $this->recipients = $recipients;
        $this->container = $container;
    }

    /**
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event) {
        $exception = $event->getException();
        
        $mailer = $this->container->get('mailer');
        var_dump( $mailer );
        die();
        $response = new Response();
        // setup the Response object based on the caught exception
        $event->setResponse($response);

        // you can alternatively set a new Exception
        // $exception = new \Exception('Some special exception');
        // $event->setException($exception);
    }
}
