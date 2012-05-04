<?php

namespace Anyx\CrosswordBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Anyx\CrosswordBundle\Document;

/**
 * CrosswordRepository
 *
 */
class CrosswordRepository extends DocumentRepository {
	
	/**
	 *
	 * @return Doctrine\ODM\MongoDB\LoggableCursor
	 */
	public function getPublicCrosswords() {
		return $this->getPublicCrosswordsQueryBuilder()->getQuery()->execute();
	}
	
	/**
	 * 
	 */
	public function getPopular( $limit = 10 ) {
		return $this->getPublicCrosswordsQueryBuilder()
			->sort( 'countSolving', 'desc' )
			->limit( $limit )
			->getQuery()
			->execute();
	}

	/**
	 *
	 */
	public function getNew( $limit = 10 ) {
		return $this->getPublicCrosswordsQueryBuilder()
			->sort( 'createdAt', 'desc' )
			->limit( $limit )
			->getQuery()
			->execute();
	}

    /**
     *
     * @param Document\User $user
     * @param int $limit
     * @param int $skip
     * @return Doctrine\ODM\MongoDB\LoggableCursor
     */
    public function getUserCrosswords( Document\User $user, $limit = 20, $skip = 0 ) {
 		return $this->findBy(
					array(
						'user.id' 		=> $user->getId()
					),
					array(
						'createdAt' => 'desc'
					),
                    $limit,
					$skip
		);
    }
    
	/**
	 *
	 */
	protected function getPublicCrosswordsQueryBuilder() {
		return $this->createQueryBuilder()
					->field('public')->equals(true)
		;
	}
}