<?php

namespace Anyx\CrosswordBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ODM\MongoDB\Events;
use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\Event\PreUpdateEventArgs;
use Anyx\CrosswordBundle\Document;

/**
 * 
 */
class CrosswordEstimateSubscriber implements EventSubscriber
{
    /**
     * 
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(Events::postPersist, Events::postUpdate, Events::postRemove);
    }
    
    /**
     * 
     * @param \Doctrine\ODM\MongoDB\Event\LifecycleEventArgs $args
     * @return boolean
     */
    public function postUpdate(LifecycleEventArgs $args)
    {
        if ($this->needCalculateRating($args)) {
            $this->calculateRating($args->getDocument()->getCrossword(), $args);
        }
    }

    /**
     * 
     * @param \Doctrine\ODM\MongoDB\Event\LifecycleEventArgs $args
     * @return boolean
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        if ($this->needCalculateRating($args)) {
            $this->calculateRating($args->getDocument()->getCrossword(), $args);
        }
    }

    /**
     * 
     * @param \Doctrine\ODM\MongoDB\Event\LifecycleEventArgs $args
     * @return boolean
     */
    public function postRemove(LifecycleEventArgs $args)
    {
        if ($this->needCalculateRating($args)) {
            $this->calculateRating($args->getDocument()->getCrossword(), $args);
        }
    }

    /**
     * 
     * @param \Doctrine\ODM\MongoDB\Event\LifecycleEventArgs $args
     * @return boolean
     */
    protected function needCalculateRating(LifecycleEventArgs $args)
    {
        return $args->getDocument() instanceof Document\CrosswordEstimate;
    }

    /**
     * 
     * @param \Anyx\CrosswordBundle\Document\Crossword $crossword
     * @param \Doctrine\ODM\MongoDB\Event\LifecycleEventArgs $args
     */
    protected function calculateRating(Document\Crossword $crossword, LifecycleEventArgs $args)
    {
        $repository = $args->getDocumentManager()->getRepository('Anyx\CrosswordBundle\Document\CrosswordEstimate');
        $crossword->setRating($repository->calcCrosswordRating($crossword));
        $args->getDocumentManager()->flush();
    }
}
