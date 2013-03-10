<?php

/**
 * 
 */

namespace Anyx\CrosswordBundle\Controller;

/**
 * 
 */
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Anyx\CrosswordBundle\Document;
use Anyx\CrosswordBundle\Request\ParamConverter\DoctrineMongoDBParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception as HttpException;
use Symfony\Component\Security\Core\Exception as SecurityException;
use Anyx\CrosswordBundle\Form\DataTransformer\TagsTransformer;
use Symfony\Component\HttpFoundation\Request;

/**
 * 
 * 
 */
class CrosswordController extends Controller
{

    /**
     * @Route("/crossword/new/", name="create_crossword")
     * @Template
     */
    public function newAction()
    {
        $form = $this->createCrosswordForm(new Document\Crossword);
        if ($this->saveCrossword($form)) {
            return $this->redirect(
                            $this->generateUrl(
                                    'crossword_edit',
                                    array(
                                        'id' => $form->getData()->getId()
                                    )
                            )
            );
        }

        return array('form' => $form->createView());
    }

    /**
     * @Route("/crossword/{id}/edit/", name="edit_crossword", options={"expose" = true})
     * @Template
     * @param string $id 
     */
    public function editAction($id)
    {
        $dm = $this->get('anyx.dm');
        $crossword = $dm->find('Anyx\CrosswordBundle\Document\Crossword', $id);

        if (empty($crossword)) {
            throw new HttpException\NotFoundHttpException('Crossword not found');
        }

        if (!$crossword->hasOwner($this->getUser())) {
            throw new SecurityException\AccessDeniedException('Wrong crossword owner');
        }

        $form = $this->createCrosswordForm($crossword);
        if ($this->saveCrossword($form)) {
            return $this->redirect(
                    $this->generateUrl(
                            'crossword_edit',
                            array(
                                'id' => $form->getData()->getId()
                            )
                    )
            );
        }

        return array(
            'form'      => $form->createView(),
            'crossword' => $crossword
        );
    }

    /**
     * @Route("/crossword/{id}/statistic/", name="crossword_statistic", options={"expose" = true})
     * @ParamConverter("crossword", class="Anyx\CrosswordBundle\Document\Crossword")
     * @Template
     */
    public function statisticAction(Document\Crossword $crossword)
    {
        if (!$crossword->hasOwner($this->getUser())) {
            throw new SecurityException\AccessDeniedException('Wrong crossword owner');
        }

        $solutionsRepository = $this->get('anyx.dm')->getRepository('Anyx\CrosswordBundle\Document\Solution');

        $solutionsCount = $solutionsRepository->getCountSolutions($crossword);
        $correctSolutionsCount = $solutionsRepository->getCountCorrectSolutions($crossword);

        return [
            'crossword' => $crossword,
            'solutionsCount' => $solutionsCount,
            'correctSolutionsCount' => $correctSolutionsCount,
        ];
    }

    /**
     * @Route("/crossword/{id}/solutions/", name="crossword_solutions")
     * @ParamConverter("crossword", class="Anyx\CrosswordBundle\Document\Crossword")
     */
    public function crosswordSolutionsAction(Document\Crossword $crossword, Request $request)
    {
        if (!$crossword->hasOwner($this->getUser())) {
            throw new SecurityException\AccessDeniedException('Wrong crossword owner');
        }

        $skip = (int) $request->get('skip', 0);

        $serializer = $this->get('serializer');
        $serializer->setGroups(array('statistic'));

        $solutionsRepository = $this->get('anyx.dm')->getRepository('Anyx\CrosswordBundle\Document\Solution');
        $solutions = $solutionsRepository->getCrosswordSolutions($crossword, $skip);

        $response = [
            'models' => array_values($solutions->toArray()),
            'totalCount' => $solutionsRepository->getCountSolutions($crossword)
        ];

        return new Response($serializer->serialize($response, 'json'));
    }

    /**
     * @Route("/random/crossword", name="random_crossword")
     * 
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function randomCrosswordAction()
    {
        $crosswordsRepo = $this->get('anyx.dm')->getRepository('Anyx\CrosswordBundle\Document\Crossword');
        $crossword = $crosswordsRepo->getRandomPublicCrossword();

        if (empty($crossword)) {
            throw $this->createNotFoundException('Crossword not found');
        }

        return new RedirectResponse(
                        $this->get('router')->generate(
                                'crossword_solve',
                                array(
                                    'id' => $crossword->getId()
                                )
                        )
        );
    }

    /**
     * @Route("/tags/autocomplete", name="tags_autocomplete")
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function tagsAutocompleteAction(Request $request)
    {

        if (!$request->isXmlHttpRequest()) {
            return $this->createNotFoundException();
        }

        $tagsTexts = array();

        $term = $request->get('term', '');
        if (mb_strlen($term) > 1) {
            $tags = $this->get('anyx.dm')->getRepository('Anyx\CrosswordBundle\Document\Tag')->getTagsByTerm($term);
            $tagsTexts = array_values(array_map(
                            function($tag) {
                                return $tag->getText();
                            }, $tags->toArray()
                    ));
        }
        return new Response(json_encode($tagsTexts));
    }

    /**
     *
     * @param Document\Crossword $crossword 
     */
    protected function createCrosswordForm(Document\Crossword $crossword)
    {
        $formBuilder = $this->createFormBuilder($crossword)
                ->add('title', 'text')
                ->add('description', 'textarea');

        $tagsRepository = $this->get('anyx.dm')->getRepository('Anyx\CrosswordBundle\Document\Tag');
        $tagsTransformer = new TagsTransformer($tagsRepository);

        $formBuilder->add(
                $formBuilder->create('tags', 'text', array(
                    'label' => 'Tags',
                    'required' => false
                ))->addModelTransformer($tagsTransformer)
        );

        if ($crossword->hasWords()) {
            $formBuilder->add('public', 'checkbox', array('required' => false));
        }

        return $formBuilder->getForm();
    }

    /**
     *
     * @param Document\Crossword $crossword
     * @return type 
     */
    protected function saveCrossword($form)
    {
        $request = $this->getRequest();
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $dm = $this->get('anyx.dm');

                $crossword = $form->getData();
                $crossword->setOwner($this->get('security.context')->getToken()->getUser());
                $dm->persist($crossword);
                $dm->flush();
                $this->get('session')->setFlash('message', 'Crossword saved successfully');
                return true;
            }
        }

        return false;
    }
}