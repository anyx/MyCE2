<?php
/**
 * 
 */
namespace Anyx\CrosswordBundle\Document;

/**
 * 
 */
use Doctrine\Common\Util\Inflector;
use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * 
 * @todo validation
 */
class Factory {
	
	/**
	 *
	 * @var type 
	 */
	protected $aliases = array();

	/**
	 *
	 * @var type
	 */
	protected $documentManager = null;
	
	/**
	 *
	 * @param array $aliases 
	 */
	public function __construct( DocumentManager $documentManager, array $aliases = array() ) {
		
		$this->setDocumentManager($documentManager);
		
		foreach( $aliases as $alias => $class ) {
			$this->addAlias($alias, $class);
		}
	}

	/**
	 *
	 * @param DocumentManager $documentManager 
	 */
	public function setDocumentManager( DocumentManager $documentManager ) {
		$this->documentManager = $documentManager;
	}
	
	/**
	 *
	 * @return DocumentManager
	 */
	public function getDocumentManager() {
		return $this->documentManager;
	}

	/**
	 *
	 * @param string $alias
	 * @param string $class 
	 */
	public function addAlias( $alias, $class ) {
		$this->aliases[$alias] = $class;
	}
	
	/**
	 *
	 * @param string $class
	 * @param array $data
	 */
	public function create( $class, array $data ) {
		
		if ( array_key_exists( $class, $this->aliases ) ) {
			$class = $this->aliases[$class];
		}
		
		$document = new $class;
		$reflectionClass = new \ReflectionClass( $document );
		
		foreach ( $data as $field => $value ) {
			$method = 'set' . Inflector::classify($field);
			if ( !method_exists($document, $method) ) {
				throw new \RuntimeException( 'Method "' . $method . '" not exist from object "' . get_class( $document ) .'"' );
			}
			
			$document->$method( $value );
		}
		
		$this->getDocumentManager()->persist($document);
		
		return $document;
	}

	/**
	 *
	 * @param type $class
	 * @param type $data
	 * @return \ArrayObject 
	 */
	public function createCollection( $class, $data ) {
		
		$collection = array();
		
		foreach ( $data as $objectData ) {
			$collection[] = $this->create( $class, $objectData );
		}
		
		return $collection;
	}
}