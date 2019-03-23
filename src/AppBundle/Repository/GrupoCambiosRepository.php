<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class GrupoCambiosRepository
 *
 * @package AppBundle\EntityRepository
 */
class GrupoCambiosRepository extends EntityRepository
{

    /**
     * @param $idcategory
     * @return array
     */
    function getGrupoCambiossByCategory($idcategory){

        $idgrupocambioss = [];
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Category');

        $query = $repository->createQueryBuilder('u');

        $query
            ->select("a.id")
            ->join("u.grupocambioss", "a")
            ->where('u.id = :category')
            ->setParameter('category', $idcategory);

        $grupocambioss =  $query->getQuery()->getResult();

        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:GrupoCambios');
        $query = $repository->createQueryBuilder('c');

        foreach($grupocambioss as $id) {


            $query
                ->where('c.id = :id')
                ->setParameter('id', $id['id']);

            $idgrupocambioss[] = $query->getQuery()->getResult()[0];
        }
        return $idgrupocambioss;
    }
}