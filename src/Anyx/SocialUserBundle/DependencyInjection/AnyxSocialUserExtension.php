<?php

namespace Anyx\SocialUserBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AnyxSocialUserExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
	
	/**
	 * 
	 */
	protected function configureFOSUserIntegration( array $config, ContainerBuilder $container ) {
		
		$dbDriver = $config['fos_user']['db_driver'];
		
		$userManager = 'anyx_social.user.manager.' . $dbDriver;
		
		$container->addAliases(array('anyx_social.user.manager' => $userManager));
	}	
}
