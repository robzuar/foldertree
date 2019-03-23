<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class AnteproyectoRepository
 * @package AppBundle\Repository
 */
class AnteproyectoRepository extends EntityRepository
{
    public function getAnteproyectosByDays($num)
    {
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Anteproyecto');

        $query = $repository->createQueryBuilder('anteproyecto');


        $query
            ->select('anteproyecto,  usuario ')
            ->innerJoin('anteproyecto.createdBy', 'usuario')
            ->where('anteproyecto.enabled = 1')
            ->andWhere("DATE_ADD( CURRENT_DATE(),:num, 'day') = anteproyecto.dateexpiration")
            ->setParameter('num', $num)

        ;

        $entities =  $query->getQuery()->getResult();


        //var_dump($entities);exit;
        return $entities;

    }

}
