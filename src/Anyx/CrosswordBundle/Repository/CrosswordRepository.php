<?php

namespace Anyx\CrosswordBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Anyx\CrosswordBundle\Document;

/**
 * CrosswordRepository
 *
 */
class CrosswordRepository extends DocumentRepository
{

    /**
     *
     * @return Doctrine\ODM\MongoDB\LoggableCursor
     */
    public function getPublicCrosswords()
    {
        return $this->getPublicCrosswordsQueryBuilder()->getQuery()->execute();
    }

    /**
     * 
     */
    public function getPopular($limit = 10)
    {
        return $this->getPopularCrosswordsQueryBuilder()
                        ->limit($limit)
                        ->getQuery()
                        ->execute();
    }

    /**
     *
     */
    public function getNew($limit = 10)
    {
        return $this->getNewCrosswordsQueryBuilder()
                        ->limit($limit)
                        ->getQuery()
                        ->execute();
    }

    /**
     *
     */
    public function getPopularCrosswordsQueryBuilder()
    {
        return $this->getPublicCrosswordsQueryBuilder()
                        ->sort('countSolvings', 'desc');
    }

    /**
     *
     */
    public function getNewCrosswordsQueryBuilder()
    {
        return $this->getPublicCrosswordsQueryBuilder()
                        ->sort('createdAt', 'desc');
    }

    /**
     *
     * @param Document\User $user
     * @param int $limit
     * @param int $skip
     * @return Doctrine\ODM\MongoDB\LoggableCursor
     */
    public function getUserCrosswords(Document\User $user, $limit = 20, $skip = 0)
    {
        return $this->findBy(
                        array(
                            'owner.id'  => $user->getId(),
                            'deleted'   => false
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
    public function getUserCrosswordsCount(Document\User $user)
    {
        return $this->createQueryBuilder()
                        ->field('owner.id')->equals($user->getId())
                        ->field('deleted')->equals(false)
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
    public function getUserCrossword(Document\User $user, $crosswordId)
    {

        if (empty($crosswordId)) {
            throw new \InvalidArgumentException('Crossword id is missing');
        }

        return $this->findOneBy(
                        array(
                            'id' => $crosswordId,
                            'owner.id' => $user->getId(),
                            'deleted' => false
                        )
        );
    }

    /**
     *
     */
    public function getPublicCrosswordsQueryBuilder()
    {
        $qb = $this->createQueryBuilder();
        return $qb
                ->field('public')->equals(true)
                ->field('deleted')->equals(false)
                ->field('words')->exists(true)
                ->field('words')->not($qb->expr()->size(0))
        ;
    }

    /**
     * 
     * @return \Anyx\CrosswordBundle\Document\Crossword
     */
    public function getRandomPublicCrossword()
    {
        $cursor = $this->getPublicCrosswordsQueryBuilder()->getQuery()->execute();
        
        if($cursor->count() > 0) {
            $cursor->skip(mt_rand(0, $cursor->count()-1));
            $cursor->rewind();

            return $cursor->current();
        }
        
        return null;
    }
}