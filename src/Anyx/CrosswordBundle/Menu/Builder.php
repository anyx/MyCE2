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
            'List crosswords' => array('route' => 'list_crosswords'),
            'Search' => array('route' => 'search_crosswords'),
            'Random crossword' => array('route' => 'random_crossword'),
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
        $menu->addChild('List crosswords', array('route' => 'list_crosswords'));
        return $menu;
    }
    
    public function bottomMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->addChild('List crosswords', array('route' => 'list_crosswords'));
        $menu->addChild('News', array('route' => 'page', 'routeParameters' => array('slug' => 'news')));
        $menu->addChild('About', array('route' => 'page', 'routeParameters' => array('slug' => 'about')));        
        $menu->addChild('Report a bug', array('route' => 'report'));        

        return $menu;
    }
}