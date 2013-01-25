<?php

namespace Anyx\UserBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class SocialCompilerPass implements CompilerPassInterface
{
    /**
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $container->setParameter('anyx_social_user.controller.login.class', 'Anyx\UserBundle\Controller\SocialLoginController');
        
        $definition = $container->getDefinition('anyx_social_user.controller.login');
        
        $definition->addMethodCall(
                    'setRouter',
                    array(new Reference('router'))
        );
    }
}
