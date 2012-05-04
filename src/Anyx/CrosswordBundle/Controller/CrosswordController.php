<?php
/**
 * 
 */
namespace Anyx\CrosswordBundle\Controller;

/**
 * 
 */
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Anyx\CrosswordBundle\Document;
use Anyx\CrosswordBundle\Request\ParamConverter\DoctrineMongoDBParamConverter;

use Symfony\Component\HttpFoundation\Response;

/**
 * 
 * 
 */
class CrosswordController extends Controller {

	/**
	 * @Template
	 */
	public function newAction() {
		
		$form = $this->createCrosswordForm( new Document\Crossword );
		if ( $this->saveCrossword( $form ) ) {
			return $this->redirect($this->generateUrl('crossword_edit', array(
				'id'	=> $form->getData()->getId()
			)));
		}
		
		return array( 'form' => $form );
	}

	/**
	 * @Route("/crossword/{id}/edit/", name="edit_crossword", options={"expose" = true})
	 * @Template
	 * @param string $id 
	 */
	public function editAction( $id ) {
		
		$dm = $this->get( 'anyx.dm' );
		$crossword = $dm->find( 'Anyx\CrosswordBundle\Document\Crossword', $id );
		
		$form = $this->createCrosswordForm( $crossword );
		if ( $this->saveCrossword( $form ) ) {
			return $this->redirect($this->generateUrl('crossword_edit', array(
				'id'	=> $form->getData()->getId()
			)));
		}
		
		return array(
			'form'	=> $form
		);
	}

    /**
     * @Route("/crossword/{id}/statistic/", name="crossword_statistic", options={"expose" = true})
     * @Template
     */
    public function statisticAction( $id ) {
        
    }


    /**
	 *
	 * @param Document\Crossword $crossword 
	 */
	protected function createCrosswordForm( Document\Crossword $crossword ) {

		$formBuilder = $this->createFormBuilder( $crossword )
							->add( 'id', 'hidden' )
							->add( 'title', 'text' )
							->add( 'description', 'textarea' )
							
		;
		
		if ( $crossword->hasWords() ) {
			$formBuilder->add('public', 'checkbox');
		}
		
		return $formBuilder->getForm();
	}
	
	/**
	 *
	 * @param Document\Crossword $crossword
	 * @return type 
	 */
	protected function saveCrossword( $form ) {
		
		$request = $this->getRequest();
		if ( $request->getMethod() == 'POST' ) {
			$form->bindRequest($request);

			if ( $form->isValid() ) {
				$dm = $this->get( 'anyx.dm' );
				$dm->persist( $form->getData() );
				$dm->flush();
				$this->get('session')->setFlash('message', 'Save succesfull');
				return true;
			}
		}
		
		return false;
	}
}