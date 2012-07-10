<?php

namespace Anyx\PageBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Anyx\PageBundle\Document;

/**
 * CrosswordRepository
 *
 */
class PageRepository extends DocumentRepository {
	
	/**
	 *
	 * @return Doctrine\ODM\MongoDB\LoggableCursor
	 */
	public function getPublicPageBySlug( $slug ) {
		return $this->createQueryBuilder()
				->field('public')->equals(true)
				->field('slug')->equals($slug)
                ->getQuery()
                ->getSingleResult();
	}
}