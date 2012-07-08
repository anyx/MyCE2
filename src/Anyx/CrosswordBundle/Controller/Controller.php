<?php

namespace Anyx\CrosswordBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

use Pagerfanta;
use Pagerfanta\Adapter\DoctrineODMMongoDBAdapter as Paginator;

/**
 * 
 */
class Controller extends BaseController {

    /**
     *
     * @param \Doctrine\ODM\MongoDB\Query\Builder $builder
     * @return \Pagerfanta\Pagerfanta 
     */
    protected function getPaginator( \Doctrine\ODM\MongoDB\Query\Builder $builder ) {
        return new Pagerfanta\Pagerfanta( new Paginator( $builder ) );
    }
}
