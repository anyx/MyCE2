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
class TagsSubscriber implements EventSubscriber
{
    /**
     * 
     */
    private $isStarted = false;

    /**
     * 
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(Events::preUpdate, Events::preRemove);
    }
    
    /**
     * 
     * @param \Doctrine\ODM\MongoDB\Event\LifecycleEventArgs $args
     * @return boolean
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        if (!$this->needSyncronyTags($args)) {
            return false;
        }
        
        $tagsRepository = $args->getDocumentManager()->getRepository('Anyx\CrosswordBundle\Document\Tag');
        
        $addedTags = $this->getAddedTags($args->getDocument(), $args);
        if (!empty($addedTags)) {
            $tagsRepository->incrementWeights($addedTags);
        }
        
        $removedTags = $this->getRemovedTags($args->getDocument(), $args);
        if (!empty($removedTags)) {
            $tagsRepository->decrementWeights($removedTags);
        }
        
        $this->synch($args);
    }
    
    public function preRemove(LifecycleEventArgs $args)
    {
        
    }
    
    /**
     * 
     * @param \Doctrine\ODM\MongoDB\Event\LifecycleEventArgs $args
     * @return boolean
     */
    protected function needSyncronyTags(LifecycleEventArgs $args)
    {
        return $args->getDocument() instanceof Document\Crossword && !$this->isStarted;
    }

    /**
     * 
     * @param \Anyx\CrosswordBundle\Listener\Document\Crossword $crossword
     * @param \Doctrine\ODM\MongoDB\Events\PreUpdateEventArgs $args
     * @return array
     */
    protected function getAddedTags(Document\Crossword $crossword, PreUpdateEventArgs $args)
    {
        $result = array();
        
        do {
            if (!$crossword->isAccessible()) {
                $result = array();
                break;
            }
            
            if ($args->hasChangedField('public')) {//если кроссворд был опубликован
                $result = $crossword->getTags();
                break;
            }
            
            if ($args->hasChangedField('tags')) {//если кроссворд были изменены теги
                $result = array_diff($crossword->getTags(), (array)$args->getOldValue('tags'));
                
            }
            
        } while(false);
        
        
        return $result;
    }
    
    /**
     * 
     * @param \Anyx\CrosswordBundle\Document\Crossword $crossword
     * @param \Doctrine\ODM\MongoDB\Event\PreUpdateEventArgs $args
     * @return array
     */
    protected function getRemovedTags(Document\Crossword $crossword, PreUpdateEventArgs $args)
    {
        $result = array();
        
        do {
            if (!$crossword->isAccessible()) {
                
                if ($args->hasChangedField('public')) {//если был деактивирован
                    $result = $crossword->getTags();
                } else {
                    $result = array();
                }
                
                break;
            }
            
            if ($args->hasChangedField('tags')) {//если кроссворд были изменены теги
                $result = array_diff((array)$args->getOldValue('tags'), $crossword->getTags());
            }
            
        } while(false);
        
        return $result;
    }
    
    /**
     * 
     */
    protected function synch(LifecycleEventArgs $args)
    {
        $this->isStarted = true;
        $args->getDocumentManager()->flush();
    }
}
