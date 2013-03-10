<?php

namespace Anyx\CrosswordBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Anyx\CrosswordBundle\Document;

/**
 * CrosswordRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SolutionRepository extends DocumentRepository
{

    /**
     * @param \Anyx\CrosswordBundle\Document\User $user
     * @param \Anyx\CrosswordBundle\Document\Crossword $crossword
     * @return Document\Solution
     */
    public function getUserSolution(Document\User $user, Document\Crossword $crossword)
    {
        return $this->findOneBy(
                        array(
                            'crossword.id' => $crossword->getId(),
                            'user.id' => $user->getId()
                        )
        );
    }

    /**
     * @param \Anyx\CrosswordBundle\Document\User $user
     * @return Doctrine\ODM\MongoDB\LoggableCursor
     */
    public function getUserSolutions(Document\User $user, $limit = 20, $skip = 0)
    {
        return $this->findBy(
                        array(
                            'user.id' => $user->getId()
                        ), array(
                            'updatedAt' => 'desc'
                        ),
                        $limit,
                        $skip
        );
    }

    /**
     * @param \Anyx\CrosswordBundle\Document\User $user
     * @return int
     */
    public function getUserSolutionsCount(Document\User $user)
    {
        return $this->createQueryBuilder()
                        ->field('user.id')->equals($user->getId())
                        ->getQuery()
                        ->count()
        ;
    }

    /**
     * 
     * @param \Anyx\CrosswordBundle\Document\Crossword $crossword
     * @return int
     */
    public function getCountSolutions(Document\Crossword $crossword)
    {
        return $this->createQueryBuilder()
                    ->field('crossword.id')->equals($crossword->getId())
                    ->count()
                    ->getQuery()
                    ->execute();
    }

    /**
     * 
     * @param \Anyx\CrosswordBundle\Document\Crossword $crossword
     * @return int
     */
    public function getCountCorrectSolutions(Document\Crossword $crossword)
    {
        return $this->createQueryBuilder()
                    ->field('crossword.id')->equals($crossword->getId())
                    ->field('correct')->equals(true)
                    ->count()
                    ->getQuery()
                    ->execute();
    }

    /**
     * 
     * @param \Anyx\CrosswordBundle\Document\Crossword $crossword
     * @param int $skip
     * @return array
     */
    public function getCrosswordSolutions(Document\Crossword $crossword, $skip = 0, $limit = 20)
    {
        return $this->createQueryBuilder()
                ->field('crossword.id')->equals($crossword->getId())
                ->sort('updatedAt', 'desc')
                ->skip($skip)
                ->limit($limit)
                ->getQuery()
                ->execute()
        ;
    }
}