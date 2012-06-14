<?php

namespace Anyx\CrosswordBundle\Tests;

require_once __DIR__.'/../../../../app/AppKernel.php';

class TestCase extends \PHPUnit_Framework_TestCase {
    
    /**
     * 
     */
    protected static $app = null;
    
    /**
     * @var array 
     */
    protected $testDocuments = array();
    
    /**
     * 
     */
    protected function getApp() {
        if ( empty( self::$app ) ) {
            self::$app = new \AppKernel('test', true);
            self::$app->boot();
        }
        
        return self::$app;
    }

    /**
     * 
     */
    protected function getContainer() {
        return $this->getApp()->getContainer();
    }
    
    /**
     *
     * @param string $class
     * @param array $data
     * @param bool $persist 
     */
    protected function createDocument( $class, $data, $persist = true, $store = true ) {
        $document = $this->getContainer()
                        ->get('anyx.document.factory')
                        ->create($class, $data, $persist);
        
        if( $store ) {
            $this->testDocuments[] = $document;
        } 
        
        return $document;
    }
    
    /**
     * 
     */
    protected function tearDown() {
        
        if ( !empty( $this->testDocuments ) ) {
            $documentManager = $this->getContainer()->get('anyx.dm');
            foreach( $this->testDocuments as $document ) {
                $documentManager->remove( $document );
            }
            $documentManager->flush();
        }
        
        parent::tearDown();  
    }
}