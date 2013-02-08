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
     * @Route("/crosswords", name="list_crosswords")
     * @Template
     */
    public function listAction(Request $request)
    {
        $sort = $request->get('sort', 'publishedAt');
        $page = $request->get('page', 1);
        
        $query = $this->buildListQuery($sort);
        $result = $this->get('foq_elastica.finder.website.crossword')->findPaginated($query);
        $result->setMaxPerPage(25);

        if (!empty($page)) {
            $result->setCurrentPage($page);
        }
        
        return array(
            'result'            => $result,
            'currentSort'       => $sort,
            'allowedSortFields' => $this->getAllowedSortFields()
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
        
        $sort = $request->get('sort', 'publishedAt');
        if ($isValid) {

            $data = $form->getData();
            $tags = explode(',', $data['tags']);
            
            $query = $this->buildSearchQuery(
                                $data['term'],
                                array_filter($tags, function($tag) { return !empty($tag); })
            );

            $sortedQuery = new \Elastica_Query($query);
            if (in_array($sort, $this->getAllowedSortFields())) {
                $sortedQuery->setSort(array(
                    $sort => 'desc'
                ));
            }
                                
            $result = $this->get('foq_elastica.finder.website.crossword')->findPaginated($sortedQuery);
            $result->setMaxPerPage(25);

            $page = (int) $request->get('page', 1);
            if (!empty($page)) {
                $result->setCurrentPage($page);
            }
        }

        return array(
            'form'              => $form->createView(),
            'result'            => $result,
            'isValid'           => $isValid,
            'allowedSortFields' => $this->getAllowedSortFields(),
            'currentSort'       => $sort,
        );
    }
    
    /**
     * 
     * @Template
     */
    public function tagsAction()
    {
        return array(
            'tags'  => $this->get('anyx.dm')->getRepository('Anyx\CrosswordBundle\Document\Tag')->getPopularTags()
        );
    }

    /**
     *
     * @param Document\Crossword $crossword 
     */
    protected function createSearchForm()
    {
        $formBuilder = $this->createFormBuilder(
                null,
                array(
                    'csrf_protection'       => false,
                )
        );

        $formBuilder->add('term', 'text', array('required' => false));
        $formBuilder->add('tags', 'tags', array('required' => false));
        
        return $formBuilder->getForm();
    }
    
    /**
     * 
     * @param string $sort
     * @return \Elastica_Query
     */
    private function buildListQuery($sort)
    {
        $query = new \Elastica_Query();
        if (in_array($sort, $this->getAllowedSortFields())) {
            $query->setSort(array(
                $sort => 'desc'
            ));
        }
        
        return $query;
    }
    
    /**
     * 
     * @return array
     */
    private function getAllowedSortFields()
    {
        return array('publishedAt', 'rating', 'countSolvings');
    }

    /**
     * 
     * @param string $text
     * @return \Elastica_Query_Bool
     */
    private function buildSearchQuery($text, $tags = array())
    {
        if (empty($text) && empty($tags)) {
            return null;
        }
        
        $query = new \Elastica_Query_Bool();
        
        if (!empty($text)) {
            $titleQuery = new \Elastica_Query_Text();
            $titleQuery->setFieldQuery('title', $text);
            $titleQuery->setFieldParam('title', 'analyzer', 'ru_snowball');
        
            $descriptionQuery = new \Elastica_Query_Text();
            $descriptionQuery->setFieldQuery('description', $text);
            $descriptionQuery->setFieldParam('description', 'analyzer', 'ru_snowball');

            $definitionsQuery = new \Elastica_Query_Text();
            $definitionsQuery->setFieldQuery('words_definitions_as_string', $text);
            $definitionsQuery->setFieldParam('words_definitions_as_string', 'analyzer', 'ru_snowball');

            $query->addShould($titleQuery);
            $query->addShould($descriptionQuery);
            $query->addShould($definitionsQuery);
        }

        $tagsQuery = new \Elastica_Query_Terms();
        
        if (empty($tags)) {
            $tagsQuery->setTerms('tags_texts', array(mb_strtolower($text)));            
            $query->addShould($tagsQuery);
            $resultQuery = $query;
        } else {
            $tagsQuery->setTerms('tags_texts', array_map(function($tag) {return mb_strtolower($tag);}, $tags));          
            $tagsQuery->setMinimumMatch(count($tags));
            
            $query->addMust($tagsQuery);
            $resultQuery = $query;
        }

        return $resultQuery;
    }
}
