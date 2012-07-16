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

    /**
     *
     * @var Container
     */
    private $container;

    /**
     *
     */
    private $messageTemplate;

    /**
     *
     * @param array $recipients
     * @param string $messageTemplate
     * @param Container $container 
     */
    public function __construct( array $recipients, $messageTemplate, Container $container ) {
        $this->recipients = $recipients;
        $this->container = $container;
        $this->messageTemplate = $messageTemplate;
    }

    /**
     *
     * @return array
     */
    public function getRecipients() {
        return $this->recipients;
    }
    
    /**
     *
     * @return string
     */
    public function getMessageTemplate() {
        return $this->messageTemplate;
    }

    /**
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event) {
        
        $exception = $event->getException();
        
        $mailer = $this->container->get('mailer');

        $message = $mailer->createMessage()
            ->setSubject('Unhandled exception')
            ->setTo( $this->getRecipients() )
            ->setFrom('no-reply@anyx.me')
            ->setContentType('text/html')
            ->setBody( $this->renderBody( $exception ) )
        ;
        
        echo($message->getBody());
        die();
        $mailer->send( $message );
    }
    
    private function renderBody( \Exception $exception ) {
        return $this->container
                ->get('templating')
                ->render(
                        $this->getMessageTemplate(),
                        array(
                            'class'     => get_class($exception),
                            'exception' => $exception,
                            'token'     => $this->container->get('security.context')->getToken()
                        )
                );
    }
}
