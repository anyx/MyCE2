<?php
// src/Acme/DemoBundle/Menu/Builder.php
namespace Anyx\CrosswordBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function crosswordsMenu(FactoryInterface $factory, array $options)
    {
        $items = array(
            'New crosswords' => array('route' => 'new_crosswords'),
            'Popular crosswords' => array('route' => 'popular_crosswords'),
            'Search' => array('route' => 'search_crosswords')
        );

        $menu = $factory->createItem('root');

        $currentRoute = $this->container->get('request')->get('_route');
        
        foreach ($items as $title => $options) {
            if ( $currentRoute == $options['route'] ) {
                $options['display'] = false;
            }
            $menu->addChild($title, $options);
        }
        
        return $menu;
    }
    
    /**
     *
     * @param FactoryInterface $factory
     * @param array $options
     * @return type 
     */
    public function topMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->addChild('Crosswords', array('route' => 'new_crosswords'));
        $menu->addChild('News', array('route' => 'page', 'routeParameters' => array('slug' => 'news')));
        $menu->addChild('About', array('route' => 'page', 'routeParameters' => array('slug' => 'about')));        
        return $menu;
    }
    
}