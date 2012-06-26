<?php

namespace Anyx\CrosswordBundle\Doctrine\Type;

use Doctrine\ODM\MongoDB\Mapping\Types\Type;

/**
 * Type point
 */
class Point extends Type {
	
	/**
	 * 
	 */
    public function convertToPHPValue($value) {
		throw new RuntimeException('Yeah, baby! ' . get_class( $this ) );
    }
	/**
	 *
	 * @param type $value
	 * @return type 
	 */
    public function convertToDatabaseValue($value) {
        return $value;
    }
}