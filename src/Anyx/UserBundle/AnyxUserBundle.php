<?php

namespace Anyx\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Anyx\UserBundle\DependencyInjection\SocialCompilerPass;

class AnyxUserBundle extends Bundle {

    /**
     * 
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function build(\Symfony\Component\DependencyInjection\ContainerBuilder $container)
     {
         parent::build($container);
         $container->addCompilerPass(new SocialCompilerPass());
     }

     /**
	 * 
	 */
	public function getParent() {
		return 'FOSUserBundle';
	}
}
