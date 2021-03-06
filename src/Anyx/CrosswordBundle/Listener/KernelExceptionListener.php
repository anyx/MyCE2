<?php

namespace Anyx\CrosswordBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\Container;

class KernelExceptionListener
{

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
    public function __construct(array $recipients, $messageTemplate, Container $container)
    {
        $this->recipients = $recipients;
        $this->container = $container;
        $this->messageTemplate = $messageTemplate;
    }

    /**
     *
     * @return array
     */
    public function getRecipients()
    {
        return $this->recipients;
    }

    /**
     *
     * @return string
     */
    public function getMessageTemplate()
    {
        return $this->messageTemplate;
    }

    /**
     *
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {

        $exception = $event->getException();

        $mailer = $this->container->get('mailer');

        $message = $mailer->createMessage()
                ->setSubject('Unhandled exception')
                ->setTo($this->getRecipients())
                ->setFrom('no-reply@anyx.me')
                ->setContentType('text/html')
                ->setBody($this->renderBody($exception))
        ;

        $mailer->send($message);
    }

    /**
     * 
     * @param \Exception $exception
     */
    private function renderBody(\Exception $exception)
    {
        $request = $this->container->get('request');
        $response = $this->container
                        ->get('templating')
                        ->render(
                                $this->getMessageTemplate(),
                                array(
                                    'class'     => get_class($exception),
                                    'exception' => $exception,
                                    'token'     => $this->container->get('security.context')->getToken(),
                                    'requestUri'=> $request->getRequestUri(),
                                    'server'    => print_r($request->server, true),
                                    'session'   => print_r($request->getSession()->all(), true),
                                )
        );
        return $response;
    }
}
