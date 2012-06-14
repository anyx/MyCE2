<?php

namespace Anyx\CrosswordBundle\Listener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Anyx\CrosswordBundle\Document;

/**
 * 
 */
class SolutionListener {

    /**
	 * 
	 * @param Event\MergeUsersEvent $event 
	 */
	public function postPersist( LifecycleEventArgs $args ) {
  
        $dm = $args->getDocumentManager();
        
        $document = $args->getDocument();
        
        if ( $document instanceof Document\Solution ) {
            $crossword = $document->getCrossword();
            $crossword->incCountSolving();
            $dm->flush();
        }
	}
}