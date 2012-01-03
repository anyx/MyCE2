<?php
/**
 * 
 */
namespace Anyx\CrosswordBundle\Document;


use Doctrine\Common\Util\Inflector;

/**
 * 
 * 
 */
class Factory {
	
	/**
	 *
	 * @var type 
	 */
	protected $aliases = array();

	/**
	 *
	 * @param array $aliases 
	 */
	public function __construct( array $aliases = array() ) {
		foreach( $aliases as $alias => $class ) {
			$this->addAlias($alias, $class);
		}
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
		
		$object = new $class;
		$reflectionClass = new \ReflectionClass( $object );
		
		foreach ( $data as $field => $value ) {
			$method = 'set' . Inflector::classify($field);
			if ( !method_exists($object, $method) ) {
				throw new \RuntimeException( 'Method "' . $method . '" not exist from object "' . get_class( $object ) .'"' );
			}
			
			$object->$method( $value );
		}
		
		return $object;
	}
}