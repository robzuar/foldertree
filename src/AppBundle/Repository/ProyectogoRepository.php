<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class ProyectogoRepository
 * @package AppBundle\Repository
 */
class ProyectogoRepository extends EntityRepository
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

    public function getProyectogosRespository()
    {
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Proyectogo');

        $query = $repository->createQueryBuilder('proyectogo');


        $query
            ->select('proyectogo ')->addOrderBy('proyectogo.name', 'ASC')
            ->innerJoin('proyectogo.incharge', 'incharge')
            ->innerJoin('proyectogo.analyst', 'analyst')
            ->andWhere('proyectogo.enabled = :estado')
            ->setParameter('estado', '1')
        ;

        $entities =  $query->getQuery()->getResult();

        //var_dump($entities);exit;
        return $entities;

    }

    public function getProyectogosByProfile($valor, $usuario)
    {
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Proyectogo');

        $query = $repository->createQueryBuilder('proyectogo');

        if($valor == 'encargado') {
            $query
                ->select('proyectogo ')->addOrderBy('proyectogo.name', 'ASC')
                ->innerJoin('proyectogo.incharge', 'incharge')
                ->Where('proyectogo.estado = :estado')
                ->andWhere('incharge.id = :id')
                ->andWhere('proyectogo.enabled = :estado')
                ->setParameter('estado', '1')
                ->setParameter('id', $usuario->getId());
        }elseif($valor == 'analista') {
            $query
                ->select('proyectogo')->addOrderBy('proyectogo.name', 'ASC')
                ->innerJoin('proyectogo.analyst', 'analyst')
                ->Where('analyst.analyst = :id')
                ->andWhere('proyectogo.enabled = :estado')
                ->setParameter('estado', '1')
                ->setParameter('id', $usuario->getId());
        }

        $entities =  $query->getQuery()->getResult();

        //var_dump($entities);exit;
        return $entities;

    }

    /**
     * @param $estados
     * @param $user
     * @param $element
     * @param $value
     * @param $role
     * @return \Doctrine\ORM\Query
     */
    public function getProyectos($estados, $user,$element, $value, $role)
    {
        $em = $this->getEntityManager();
        $query =$em->createQueryBuilder();
        $estadoFolder = array_search('FOLDER', $estados);
        //dump($estadoFolder);exit();
        $query
            ->select('proyecto','task','checkers', 'assistents','doc')
            ->from('AppBundle:Proyectogo', 'proyecto')
            ->join('proyecto.taskdocuments', 'task')
            ->join('task.document', 'doc')
            ->leftJoin('task.checkers', 'checkers')
            ->leftJoin('checkers.checker', 'check')
            ->leftJoin('task.assistents', 'assistents')
            ->join('assistents.assistent','assis')
            ->where('task is not null')
            ->andWhere('task.estado != :estado')
        ;

        if($element == null){

        }else{

        }

        if($element == 'idproyecto'){
            $query
                ->andWhere('proyecto.id = :proyecto');
        }
        if(in_array('ROLE_ENCARGADO',$role)){
            $query
                ->andWhere('check.id = :user');
        }elseif(in_array('ROLE_ANALISTA',$role)){
            $query
                ->andWhere('assis.id = :user');
        }
        if($element == 'idproyecto'){
            $query
                ->setParameter('proyecto', $value);
        }
        if(in_array('ROLE_ENCARGADO',$role)){
            $query
                ->setParameter('user', $user);

        }elseif(in_array('ROLE_ANALISTA',$role)){
            $query
                ->setParameter('user', $user);
        }
        //dump($query);exit;
        return $query
            ->setParameter('estado', $estadoFolder)
            ->getQuery();
    }

    /**
     * @return \Doctrine\ORM\Query
     */
    public function getProyectosNoEmptyStart()
    {
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Proyectogo');

        $query = $repository->createQueryBuilder('proyectogo');
        //dump($estadoFolder);exit();
        $query
            ->select('proyectogo')
            ->andWhere('proyectogo.startedat is not null')
        ;
        $entities =  $query->getQuery()->getResult();
        return $entities;
    }
}
