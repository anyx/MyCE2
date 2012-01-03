<?php

namespace Anyx\CrosswordBundle\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ODM\MongoDB\DocumentManager;

/*
 * This file is part of the Symfony framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * DoctrineMongoDBConverter.
 *
 */
class DoctrineMongoDBParamConverter implements ParamConverterInterface
{
    protected $documentManager;

    public function __construct( DocumentManager $documentManager )
    {
        $this->documentManager = $documentManager;
    }

    public function apply(Request $request, ConfigurationInterface $configuration)
    {
        $class = $configuration->getClass();
        $options = $this->getOptions($configuration);

        // find by identifier?
        if (false === $object = $this->find($class, $request, $options)) {
            // find by criteria
            if (false === $object = $this->findOneBy($class, $request, $options)) {
                throw new \LogicException('Unable to guess how to get a Doctrine instance from the request information.');
            }
        }

        if (null === $object && false === $configuration->isOptional()) {
            throw new NotFoundHttpException(sprintf('%s object not found.', $class));
        }

        $request->attributes->set($configuration->getName(), $object);
    }

    protected function find($class, Request $request, $options)
    {
        if (!$request->attributes->has('id')) {
            return false;
        }
        
        return $this->documentManager->find($class, $request->attributes->get('id'));
    }

    protected function findOneBy($class, Request $request, $options)
    {
        $criteria = array();
        $metadata = $this->documentManager->getClassMetadata($class);
        foreach ($request->attributes->all() as $key => $value) {
            if ($metadata->hasField($key)) {
                $criteria[$key] = $value;
            }
        }

        if (!$criteria) {
            return false;
        }
        
        return $this->documentManager->getRepository($class)->findOneBy($criteria);
    }

    public function supports(ConfigurationInterface $configuration)
    {
        if (null === $this->documentManager) {
            return false;
        }

        if (null === $configuration->getClass()) {
            return false;
        }
        
        $options = $this->getOptions($configuration);

        try {
            $this->documentManager->getClassMetadata($configuration->getClass());
            
            return true;
        } catch (MappingException $e) {
            return false;
        } catch (\ErrorException $e) {
            return false;
        }
    }

    protected function getOptions(ConfigurationInterface $configuration)
    {
        return array_replace(array(
            'document_manager' => 'default',
        ), $configuration->getOptions());
    }
}