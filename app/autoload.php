<?php

use Symfony\Component\ClassLoader\UniversalClassLoader;
use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = new UniversalClassLoader();
$loader->registerNamespaces(array(
    'Symfony'          => array(__DIR__.'/../vendor/symfony/src', __DIR__.'/../vendor/bundles'),
    'Sensio'           => __DIR__.'/../vendor/bundles',
    'JMS'              => __DIR__.'/../vendor/bundles',

	'Doctrine\\Common' => __DIR__.'/../vendor/doctrine-common/lib',
    'Doctrine\\DBAL'   => __DIR__.'/../vendor/doctrine-dbal/lib',
    'Doctrine'         => __DIR__.'/../vendor/doctrine/lib',
    'Doctrine\\ODM\\MongoDB'    => __DIR__.'/../vendor/doctrine-mongodb-odm/lib',
    'Doctrine\\MongoDB'         => __DIR__.'/../vendor/doctrine-mongodb/lib',
	
    'Monolog'          => __DIR__.'/../vendor/monolog/src',
    'Assetic'          => __DIR__.'/../vendor/assetic/src',
    'Metadata'         => __DIR__.'/../vendor/metadata/src',
	//
	'FOS'              => __DIR__.'/../vendor/bundles',
	//
    'Knp'              => __DIR__.'/../vendor/bundles',
    'Knp\Bundle'       => __DIR__.'/../vendor/bundles',
    'Knp\Menu'         => __DIR__.'/../vendor/KnpMenu/src',
    //
    'WhiteOctober\PagerfantaBundle' => __DIR__.'/../vendor/bundles',
    'Pagerfanta'       => __DIR__.'/../vendor/pagerfanta/src',
    //
    'Buzz'             => __DIR__.'/../vendor/Buzz/lib',
    'Sonata'           => __DIR__.'/../vendor/bundles',
    'Application'      => __DIR__,
    'FOQ'              => __DIR__.'/../vendor/bundles',
	'Anyx'			   => __DIR__.'/../vendor/bundles',
));

$loader->registerPrefixes(array(
    'Twig_Extensions_'  => __DIR__.'/../vendor/twig-extensions/lib',
    'Twig_'             => __DIR__.'/../vendor/twig/lib',
    'Elastica_'         => __DIR__.'/../vendor/elastica/lib',
));

// intl
if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../vendor/symfony/src/Symfony/Component/Locale/Resources/stubs/functions.php';

    $loader->registerPrefixFallbacks(array(__DIR__.'/../vendor/symfony/src/Symfony/Component/Locale/Resources/stubs'));
}

$loader->registerNamespaceFallbacks(array(
    __DIR__.'/../src',
));
$loader->register();

AnnotationRegistry::registerLoader(function($class) use ($loader) {
    $loader->loadClass($class);
    return class_exists($class, false);
});

AnnotationRegistry::registerFile(
		__DIR__.'/../vendor/doctrine-mongodb-odm/lib/Doctrine/ODM/MongoDB/Mapping/Annotations/DoctrineAnnotations.php'
);

// Swiftmailer needs a special autoloader to allow
// the lazy loading of the init file (which is expensive)
require_once __DIR__.'/../vendor/swiftmailer/lib/classes/Swift.php';
Swift::registerAutoload(__DIR__.'/../vendor/swiftmailer/lib/swift_init.php');

/**
 * 
 * @todo find better place for this
 */
\Doctrine\ODM\MongoDB\Mapping\Types\Type::addType('point', 'Anyx\CrosswordBundle\Doctrine\Type\Point');