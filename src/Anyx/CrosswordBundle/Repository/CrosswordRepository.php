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
		return $this->getPopularCrosswordsQueryBuilder()
			->limit( $limit )
			->getQuery()
			->execute();
	}

	/**
	 *
	 */
	public function getNew( $limit = 10 ) {
		return $this->getNewCrosswordsQueryBuilder()
			->limit( $limit )
			->getQuery()
			->execute();
	}

	/**
	 *
	 */
	public function getPopularCrosswordsQueryBuilder() {
		return $this->getPublicCrosswordsQueryBuilder()
			->sort( 'countSolving', 'desc' );
	}

	/**
	 *
	 */
	public function getNewCrosswordsQueryBuilder() {
		return $this->getPublicCrosswordsQueryBuilder()
			->sort( 'createdAt', 'desc' );
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
						'owner.id' => $user->getId()
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
     * @param Document\User $user
     * @return int $skip
     */
    public function getUserCrosswordsCount( Document\User $user ) {
		return $this->createQueryBuilder()
                    ->field( 'owner.id' )->equals( $user->getId() )
                    ->getQuery()
                    ->count()
        ;   
    }
    
    /**
     *
     * @param Document\User $user
     * @param int $crosswordId
     * @return Document\Crossword
     */
    public function getUserCrossword( Document\User $user, $crosswordId ) {
 		
        if ( empty( $crosswordId ) ) {
            throw new \InvalidArgumentException( 'Crossword id is missing' );
        }
        
        return $this->findOneBy(
					array(
                        'id'            => $crosswordId,
						'owner.id' 		=> $user->getId()
					)
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