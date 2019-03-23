<?php

namespace AppBundle\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * Class DocumentRepository
 *
 * @package AppBundle\EntityRepository
 *
 * @author Roberto Zuñiga Araya <roberto.zuniga.araya@gmail.com>
 */
class DocumentRepository extends NestedTreeRepository
{

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $query = $this
            ->createQueryBuilder('document')
            ->andWhere('document.enabled = 1')
            ->orderBy('document.root, document.lft', 'ASC')
            ->getQuery()

            ;

    }

    /**
     * @return mixed
     */
    public function getEnabledByProyect()
    {
        return $query = $this
            ->createQueryBuilder('document')
            ->andWhere('document.enabled = 1')
            ->orderBy('document.root, document.lft', 'ASC')
            ->getQuery()

            ;

    }

    public function haveChild($id)
    {
        return $query = $this
            ->createQueryBuilder('document')
            ->where('document.root = :root')
            ->andWhere('document.enabled = 1')
            ->setParameter('root', $id)
            ->orderBy('document.root, document.lft', 'ASC')
            ->getQuery()
            ->getArrayResult()

            ;
    }
}
