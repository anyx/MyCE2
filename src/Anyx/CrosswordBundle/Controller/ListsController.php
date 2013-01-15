<?php

namespace Anyx\CrosswordBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Anyx\CrosswordBundle\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Validator\Constraints as Validator;

/**
 * 
 */
class ListsController extends Controller
{

    /**
     * @Route("/new", name="new_crosswords")
     * @Template("AnyxCrosswordBundle:Lists:new.html.twig")
     */
    public function listNewAction()
    {
        $crosswordsRepository = $this->get('anyx.dm')->getRepository('Anyx\CrosswordBundle\Document\Crossword');
        return array(
            'result' => $this->getPaginator($crosswordsRepository->getNewCrosswordsQueryBuilder())
        );
    }

    /**
     * @Route("/popular", name="popular_crosswords")
     * @Template("AnyxCrosswordBundle:Lists:popular.html.twig")
     */
    public function listPopularAction()
    {
        $crosswordsRepository = $this->get('anyx.dm')->getRepository('Anyx\CrosswordBundle\Document\Crossword');
        return array(
            'result' => $this->getPaginator($crosswordsRepository->getPopularCrosswordsQueryBuilder())
        );
    }

    /**
     * @Route("/search", name="search_crosswords")
     * @Template
     */
    public function searchAction(Request $request)
    {
        $form = $this->createSearchForm();
        $form->bindRequest($request);

        $result = array();
        $isValid = $form->isValid();
        if ($isValid) {

            $data = $form->getData();
            $query = $this->buildSearchQuery($data['term']);
            
            $result = $this->get('foq_elastica.finder.website.crossword')->findPaginated($query);
            $result->setMaxPerPage(25);

            $page = (int) $request->get('page', 1);
            if (!empty($page)) {
                $result->setCurrentPage($page);
            }
        }

        return array(
            'form' => $form->createView(),
            'result' => $result,
            'isValid' => $isValid
        );
    }

    /**
     *
     * @param Document\Crossword $crossword 
     */
    protected function createSearchForm()
    {
        $searchValidators = new Validator\Collection(
                                    array(
                                        'term' => array(
                                            new Validator\MinLength(3),
                                            new Validator\NotBlank(array('message' => ''))
                                        ),
                                    )
        );

        $formBuilder = $this->createFormBuilder(
                null,
                array(
                    'csrf_protection' => false,
                    'validation_constraint' => $searchValidators
                )
        );

        $formBuilder->add('term', 'text', array('required' => false));

        return $formBuilder->getForm();
    }
    
    /**
     * 
     * @param string $text
     * @return \Elastica_Query_Bool
     */
    private function buildSearchQuery($text)
    {
        $titleQuery = new \Elastica_Query_Text();
        $titleQuery->setFieldQuery('title', $text);
        $titleQuery->setFieldParam('title', 'analyzer', 'ru_snowball');

        $descriptionQuery = new \Elastica_Query_Text();
        $descriptionQuery->setFieldQuery('description', $text);
        $descriptionQuery->setFieldParam('description', 'analyzer', 'ru_snowball');

        $definitionsQuery = new \Elastica_Query_Text();
        $definitionsQuery->setFieldQuery('words_definitions_as_string', $text);
        $definitionsQuery->setFieldParam('words_definitions_as_string', 'analyzer', 'ru_snowball');

        $query = new \Elastica_Query_Bool();
        $query->addShould($titleQuery);
        $query->addShould($descriptionQuery);
        $query->addShould($definitionsQuery);
        
        return $query;
    }
}
