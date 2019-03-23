<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class PermisoRepository
 *
 * @package AppBundle\EntityRepository
 */
class PermisoRepository extends EntityRepository
{

    /**
     * @param $idcategory
     * @return array
     */
    function getPermisosByCategory($idcategory){

        $idpermisos = [];
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Category');

        $query = $repository->createQueryBuilder('u');

        $query
            ->select("a.id")
            ->join("u.permisos", "a")
            ->where('u.id = :category')
            ->setParameter('category', $idcategory);

        $permisos =  $query->getQuery()->getResult();

        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Permiso');
        $query = $repository->createQueryBuilder('c');

        foreach($permisos as $id) {


            $query
                ->where('c.id = :id')
                ->setParameter('id', $id['id']);

            $idpermisos[] = $query->getQuery()->getResult()[0];
        }
        return $idpermisos;
    }
}