<?php

namespace Anyx\UserBundle\Controller;

use FOS\UserBundle\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

/**
 * 
 */
class ProfileController extends BaseController\ProfileController
{

    /**
     * @Route("/profile/", name="user_profile")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }
    /**
     * @Route("/profile/created-crosswords/") 
     */
    public function createdAction(Request $request)
    {

        $skip = (int) $request->get('skip', 0);

        $securityContext = $this->container->get('security.context');

        if (!$securityContext->isGranted('ROLE_USER')) {
            throw new AccessDeniedException;
        }

        $currentUser = $securityContext->getToken()->getUser();

        $crosswordsRepository = $this->container->get('anyx.dm')->getRepository('Anyx\CrosswordBundle\Document\Crossword');

        $crosswords = $crosswordsRepository->getUserCrosswords($currentUser, 20, $skip);

        $response = array(
            'models' => array_values($crosswords->toArray()),
            'totalCount' => $crosswordsRepository->getUserCrosswordsCount($currentUser)
        );


        $serializer = $this->container->get('serializer');
        $serializer->setGroups(array('profile'));
        return new Response($serializer->serialize($response, 'json'));
    }

    /**
     * @Route("/profile/solved-crosswords/")
     */
    public function solvedAction(Request $request)
    {
        $skip = (int) $request->get('skip', 0);

        $securityContext = $this->container->get('security.context');

        if (!$securityContext->isGranted('ROLE_USER')) {
            throw new AccessDeniedException;
        }

        $currentUser = $securityContext->getToken()->getUser();

        $solutionsRepository = $this->container->get('anyx.dm')->getRepository('Anyx\CrosswordBundle\Document\Solution');

        $solutions = $solutionsRepository->getUserSolutions($currentUser, 20, $skip);

        $response = array(
            'models' => array_values($solutions->toArray()),
            'totalCount' => $solutionsRepository->getUserSolutionsCount($currentUser)
        );

        $serializer = $this->container->get('serializer');
        $serializer->setGroups(array('profile'));

        return new Response($serializer->serialize($response, 'json'));
    }

    /**
     * @Route("/profile/remove-crossword/{id}", name="remove_crossword", options={"expose" = true})
     */
    public function removeCrosswordAction($id)
    {

        $result = false;
        $message = '';

        $dm = $this->container->get('anyx.dm');

        $currentUser = $this->container->get('security.context')->getToken()->getUser();

        $crosswordsRepository = $dm->getRepository('Anyx\CrosswordBundle\Document\Crossword');

        $crossword = $crosswordsRepository->getUserCrossword($currentUser, $id);

        if (empty($crossword)) {
            $message = $this->container->get('translator')->trans('Crossword not found');
        } else {
            $dm->remove($crossword);
            $dm->flush();
            $result = true;
        }

        return new Response(json_encode(array(
                            'success' => $result,
                            'message' => $message
                        )));
    }

}
