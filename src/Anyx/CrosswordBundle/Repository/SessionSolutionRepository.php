<?php

namespace Anyx\CrosswordBundle\Repository;

use Symfony\Component\HttpFoundation\Session\Session;
use Anyx\CrosswordBundle\Document\Crossword;
use Anyx\CrosswordBundle\Document\Solution;
use Symfony\Component\HttpFoundation\ParameterBag;

class SessionSolutionRepository
{
    protected $session;
    
    /**
     * 
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
        
    }

    /**
     * 
     * @return Symfony\Component\HttpFoundation\Session\Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * 
     * @param \Anyx\CrosswordBundle\Document\Crossword $crossword
     */
    public function findSolution(Crossword $crossword)
    {
        return $this->getSession()->get('solutions['.$crossword->getId().']');
    }
    
    /**
     * 
     * @param \Anyx\CrosswordBundle\Document\Solution $solution
     */
    public function saveSolution(Solution $solution)
    {
        return $this->getSession()->set('solutions['.$solution->getCrossword()->getId().']', $solution);
    }
}
