<?php

namespace Anyx\CrosswordBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Anyx\CrosswordBundle\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * 
 */
class SolvingController extends Controller
{

    /**
     * @ParamConverter("crossword", class="Anyx\CrosswordBundle\Document\Crossword")
     * @Route("/solve/{id}/", name="crossword_solve",options={"expose" = true})
     * @Template
     */
    public function indexAction(Document\Crossword $crossword)
    {
        $securityContext = $this->get('security.context');

        $serializedSolution = null;

        $serializer = $this->get('serializer');

        if ($securityContext->isGranted('ROLE_USER')) {
            $user = $this->get('security.context')->getToken()->getUser();
            $documentManager = $this->get('anyx.dm');
            $solutionRepository = $documentManager->getRepository('Anyx\CrosswordBundle\Document\Solution');
            $solution = $solutionRepository->getUserSolution($user, $crossword);
        } else {
            $solution = $this->get('solution.session.repository')->findSolution($crossword);
        }

        if (!empty($solution)) {
            $serializer->setGroups(array('solving'));
            $serializedSolution = $serializer->serialize($solution, 'json');            
        }
        
        return array(
            'crossword' => $crossword,
            'solution'  => $serializedSolution,
        );
    }

    /**
     * @ParamConverter("crossword", class="Anyx\CrosswordBundle\Document\Crossword")
     * @Route("/solve/{id}/save", name="crossword_save_solution", options={"expose" = true})
     * @Method({"POST"})
     */
    public function saveAction(Document\Crossword $crossword)
    {
        $solutionData = $this->get('request')->get('solution');

        $documentManager = $this->get('anyx.dm');
        $securityContext = $this->get('security.context');

        $factory = $this->get('anyx.document.factory');
        $user = $securityContext->getToken()->getUser();

        $answers = array();
        foreach ($crossword->getWords() as $word) {
            if (array_key_exists($word->getId(), $solutionData)) {
                $answers[] = $factory->create(
                                'Answer',
                                array(
                                    'wordId' => $word->getId(),
                                    'text' => $solutionData[$word->getId()]
                                ),
                                false
                );
            }
        }

        if ($securityContext->isGranted('ROLE_USER')) {
            $solution = $documentManager->getRepository('Anyx\CrosswordBundle\Document\Solution')->getUserSolution($user, $crossword);
        }
        
        if (empty($solution)) {
            $solution = $factory->create(
                    'Anyx\CrosswordBundle\Document\Solution',
                    array(
                        'crossword' => $crossword
                    )
            );
        }
        
        $solution->setAnswers($answers);
        
        if($securityContext->isGranted('ROLE_USER')) {
            $solution->setUser($user);
        } else {
            $this->get('solution.session.repository')->saveSolution($solution);
        }


        $documentManager->flush();

        return new Response(json_encode(array(
                            'success' => true,
                            'correct' => $solution->isCorrect()
        )));
    }

}
