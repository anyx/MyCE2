<?php

namespace Anyx\SocialUserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('anyx_social_user');

		$this->buildFOSUserIntegration($rootNode->children()->arrayNode('fos_user'));

		$this->buildAccountsMap($rootNode->children()->arrayNode('accounts'));
		
		return $treeBuilder;
    }
	
	/**
	 * @todo false by default
	 * @param ArrayNodeDefinition $rootNode 
	 */
	private function buildFOSUserIntegration( ArrayNodeDefinition $rootNode ) {
		$rootNode
			->children()
				->scalarNode('db_driver')
				->end()
		;		
	}
	
	/**
	 *
	 */
	private function buildAccountsMap( ArrayNodeDefinition $rootNode ) {
	
		$rootNode
			->children()
			->arrayNode('map')
				->useAttributeAsKey('services')
					->prototype('array')
						->children()
							->scalarNode('accountId')
							->end()	
							->scalarNode('userName')
							->end()
		;
	}	
}
