<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\DoctrineBundle\DoctrineBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),

			new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
			//
			new Symfony\Bundle\DoctrineMongoDBBundle\DoctrineMongoDBBundle(),
			new JMS\SerializerBundle\JMSSerializerBundle($this),
			//
			//new FOS\RestBundle\FOSRestBundle(),
			new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
			//
			new FOS\UserBundle\FOSUserBundle(),
			//
			new Sensio\Bundle\BuzzBundle\SensioBuzzBundle(),
            //
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            //
            new Sonata\CacheBundle\SonataCacheBundle(),
            new Sonata\jQueryBundle\SonatajQueryBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\DoctrineMongoDBAdminBundle\SonataDoctrineMongoDBAdminBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            //
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
			//
            new FOQ\ElasticaBundle\FOQElasticaBundle(),
            //
			new Anyx\CrosswordBundle\AnyxCrosswordBundle(),
            new Anyx\UserBundle\AnyxUserBundle(),
            new Anyx\SocialBundle\AnyxSocialBundle(),
            new Anyx\SocialUserBundle\AnyxSocialUserBundle(),
            new Anyx\PageBundle\AnyxPageBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
