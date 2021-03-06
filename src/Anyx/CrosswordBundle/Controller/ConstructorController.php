<?php

/**
 * 
 */

namespace Anyx\CrosswordBundle\Controller;

use Anyx\CrosswordBundle\Document;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * 
 * @Route("/constructor")
 */
class ConstructorController extends Controller
{

    /**
     * 
     * @Route("/{id}", name="constructor", options={"expose" = true})
     * @ParamConverter("crossword", class="Anyx\CrosswordBundle\Document\Crossword")
     * @Template
     */
    public function indexAction(Document\Crossword $crossword)
    {
        if (!$crossword->hasOwner($this->getUser())) {
            throw new AccessDeniedException();
        }
        $serializer = $this->get('serializer');
        $serializer->setGroups(array('edit'));
        return array(
            'crossword' => $crossword,
            'words'     => $crossword->hasWords() ? $serializer->serialize($crossword->getWordsAsArray(), 'json') : '[]'
        );
    }

    /**
     *
     * @Route("/{id}/save", name="constructor_save", options={"expose" = true})
     * @ParamConverter("crossword", class="Anyx\CrosswordBundle\Document\Crossword")
     * @Method({"POST"})
     */
    public function saveAction(Document\Crossword $crossword, Request $reguest)
    {
        $words = $reguest->get('words');

        if (!empty($words)) {
            $wordsDocuments = $this->get('anyx.document.factory')->createCollection('Word', $words, false);
            $crossword->updateWords($wordsDocuments);
        } else {
            $crossword->removeWords();
        }

        if ($crossword->isPublic() && $crossword->getCountWords() == 0) {
            $crossword->setPublic(false);
        }
        
        $dm = $this->get('anyx.dm');

        $dm->persist($crossword);

        $dm->flush();

        $serializer = $this->get('serializer');
        $serializer->setGroups(array('edit'));
        $router = $this->get('router');
        return new Response(
                        $serializer->serialize(
                                array(
                                    'success'   => true,
                                    'crossword' => $crossword,
                                    'solvingUrl'=> $router->generate('crossword_solve', array('id' => $crossword->getId())),
                                    'editUrl'   =>$router->generate('edit_crossword', array('id' => $crossword->getId())),
                                    'words'     => array_values($crossword->getWords()->toArray())
                                    ),
                                'json'
                        )
        );
    }

}