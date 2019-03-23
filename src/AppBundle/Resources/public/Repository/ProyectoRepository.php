<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class ProyectoRepository
 * @package AppBundle\Repository
 */
class ProyectoRepository extends EntityRepository
{

    public function getUsersByRole($role)
    {
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Usuario');

        $query = $repository->createQueryBuilder('usuario');


        return $query
            ->select('usuario')
            ->where('usuario.roles = :role')
            ->setParameter('role', $role)

        ;

        //$entities =  $query->getQuery()->getResult();

        //var_dump($entities);exit;
        //return $entities;

    }

    public function getProyectosRespository()
    {
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Proyecto');

        $query = $repository->createQueryBuilder('proyecto');


        $query
            ->select('proyecto ')
            ->innerJoin('proyecto.incharge', 'incharge')
            ->innerJoin('proyecto.analyst', 'analyst')
            ->andWhere('proyecto.enabled = :estado')
            ->setParameter('estado', 'true')

        ;

        $entities =  $query->getQuery()->getResult();

        //var_dump($entities);exit;
        return $entities;

    }



}
