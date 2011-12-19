<?php
/**
 * 
 */
namespace Anyx\CrosswordBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Anyx\CrosswordBundle\Document;

/**
 * 
 */
class ConstructorController extends Controller {

	/**
	 * 
	 */
	public function indexAction() {
		return $this->render('AnyxCrosswordBundle:Constructor:index.html.twig', array());
	} 
}