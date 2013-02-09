<?php

namespace Anyx\CrosswordBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Anyx\CrosswordBundle\Document;

/**
 * 
 */
class CrosswordEstimateRepository extends DocumentRepository
{
    /**
     * 
     * @param \Anyx\CrosswordBundle\Document\User $user
     * @param \Anyx\CrosswordBundle\Document\Crossword $crossword
     * @return \Anyx\CrosswordBundle\Document\CrosswordEstimate
     */
    public function getUserEstimate(Document\User $user, Document\Crossword $crossword)
    {
        return $this->findOneBy(array(
            'user.id'      => $user->getId(),
            'crossword.id' => $crossword->getId()
        ));
    }
    
    /**
     * 
     * @param \Anyx\CrosswordBundle\Document\Crossword $crossword
     * @return float
     */
    public function calcCrosswordRating(Document\Crossword $crossword)
    {
        $result = 0;
        $estimates = $this->findBy(array(
            'crossword.id' => $crossword->getId()
        ));
        
        if (count($estimates) > 0 ) {
            $sumEstimates = 0;
            foreach ($estimates as $estimate) {
                $sumEstimates += $estimate->getEstimate();
            }
            $result = $sumEstimates / count($estimates);
        }
        
        return $result;
    }
}
