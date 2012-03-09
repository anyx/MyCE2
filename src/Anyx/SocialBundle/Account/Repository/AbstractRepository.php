<?php

namespace Anyx\SocialBundle\Account\Repository;

/**
 *
 */
abstract class Decorator {

	/**
	 *
	 */
	protected $repository;

	/**
	 *
	 * @param string $repository 
	 */
	public function __construct( $repository ) {
		$this->repository = $repository;
	}
	
	public function findUserByAccount( $account ) {
		
	}
}