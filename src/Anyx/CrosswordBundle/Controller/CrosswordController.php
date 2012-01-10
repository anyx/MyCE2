<?php
/**
 * 
 */
namespace Anyx\CrosswordBundle\Controller;

/**
 * 
 */
use Symfony\Bundle\FrameworkBundle\Controller\Controller,
	// 	
	FOS\RestBundle\Controller\Annotations\View,
	Anyx\CrosswordBundle\Document,
	Anyx\CrosswordBundle\Request\ParamConverter\DoctrineMongoDBParamConverter,

	//tmp
	Symfony\Component\HttpFoundation\Response
;

/**
 * 
 * 
 */
class CrosswordController extends Controller {

	/**
	 * @View
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
	 *
	 * @View
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