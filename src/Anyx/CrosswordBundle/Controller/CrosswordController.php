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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception as HttpException;
use Symfony\Component\Security\Core\Exception as SecurityException;
use Symfony\Component\Form\FormError;

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
            return $this->redirect($this->generateUrl(
                                'crossword_edit',
                                array(
                                    'id' => $form->getData()->getId()
                                )
            ));
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
            return $this->redirect($this->generateUrl('crossword_edit', array(
                                'id' => $form->getData()->getId()
                            )));
        }

        return array(
            'form'      => $form->createView(),
            'crossword' => $crossword
        );
    }

    /**
     * @Route("/crossword/{id}/statistic/", name="crossword_statistic", options={"expose" = true})
     * @Template
     */
    public function statisticAction($id)
    {
        
    }

    /**
     * @Route("/crossword/random/", name="random_crossword")
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
     *
     * @param Document\Crossword $crossword 
     */
    protected function createCrosswordForm(Document\Crossword $crossword)
    {
        $formBuilder = $this->createFormBuilder($crossword)
                ->add('title', 'text')
                ->add('description', 'textarea')
        ;

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