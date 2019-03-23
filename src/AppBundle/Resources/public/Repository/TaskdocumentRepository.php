<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class TaskdocumentRepository
 * @package AppBundle\Repository
 */
class TaskdocumentRepository extends EntityRepository
{
    public function getTaskDocsByUser($user, $valor)
    {
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Taskdocument');

        $query = $repository->createQueryBuilder('task');

        if($valor == 'encargado') {
            $query
               ->select('task,  proyectogo ')->addOrderBy('proyectogo.name', 'ASC')
               ->innerJoin('task.proyectogo', 'proyectogo')
               ->innerJoin('proyectogo.incharge', 'incharge')
               ->where('incharge.id = :usuario')
               ->setParameter('usuario', $user->getId());
        }elseif($valor == 'analista'){
            $query
                ->select('task,  proyectogo ')->addOrderBy('proyectogo.name', 'ASC')
                ->innerJoin('task.proyectogo', 'proyectogo')
                ->innerJoin('proyectogo.analyst', 'analyst')
                ->where('analyst.id = :usuario')
                ->setParameter('usuario', $user->getId());
        }
                $entities =  $query->getQuery()->getResult();

        //var_dump($entities);exit;
        return $entities;

    }

    public function getPendingTaskDocsByUser($user, $estado, $valor)
    {
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Taskdocument');

        $query = $repository->createQueryBuilder('task');

        if($valor == 'encargado') {
            $query
                ->select('task,  proyectogo ')->addOrderBy('proyectogo.name', 'ASC')
                ->innerJoin('task.proyectogo', 'proyectogo')
                ->innerJoin('proyectogo.incharge', 'incharge')
                ->where('incharge.id = :usuario')
                ->andHaving('task.estado = :estado')
                ->setParameter('usuario', $user->getId())
                ->setParameter('estado', $estado);
        }elseif($valor == 'analista'){
            $query
                ->select('task,  proyectogo ')->addOrderBy('proyectogo.name', 'ASC')
                ->innerJoin('task.proyectogo', 'proyectogo')
                ->innerJoin('proyectogo.analyst', 'analyst')
                ->where('analyst.id = :usuario')
                ->andHaving('task.estado = :estado')
                ->setParameter('usuario', $user->getId())
                ->setParameter('estado', $estado);
            }
        $entities =  $query->getQuery()->getResult();

        //var_dump($entities);exit;
        return $entities;

    }

    public function getTaskDocsByUserProyect($user, $id)
    {
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Taskdocument');

        $query = $repository->createQueryBuilder('task');


        $query
            ->select('task,  proyectogo ')->addOrderBy('proyectogo.name', 'ASC')
            ->innerJoin('task.proyectogo', 'proyectogo')
            ->innerJoin('proyectogo.incharge', 'incharge')
            ->where('incharge.id = :usuario')
            ->andWhere('proyectogo.id = :id')
            ->setParameter('usuario', $user->getId())
            ->setParameter('id', $id)

        ;

        $entities =  $query->getQuery()->getResult();

        //var_dump($entities);exit;
        return $entities;

    }

    public function getPendingTaskDocsByUserProyect($user, $id)
    {
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Taskdocument');

        $query = $repository->createQueryBuilder('task');


        $query
            ->select('task,  proyectogo,incharge ')
            ->innerJoin('task.proyectogo', 'proyectogo')
            ->innerJoin('proyectogo.incharge', 'incharge')
            ->where('incharge.id = :usuario')
            ->andWhere('proyectogo.id = :id')
            ->andWhere('task.estado = :estado')
            ->setParameter('usuario', $user->getId())
            ->setParameter('id', $id)
            ->setParameter('estado', 'PENDIENTE')

        ;

        $entities =  $query->getQuery()->getResult();

        //var_dump($entities);exit;
        return $entities;

    }

    public function getTasksByUserProyect($proyectogo, $usuario, $valor)
    {
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Taskdocument');

        $query = $repository->createQueryBuilder('task');

        if($valor === 'admin' ){
            $query
                ->select('task,  proyectogo ')->addOrderBy('proyectogo.name', 'ASC')
                ->innerJoin('task.proyectogo', 'proyectogo')
                ->where('proyectogo.id = :proyectogo')
                ->setParameter('proyectogo', $proyectogo);
        } elseif( $valor === 'encargado') {
            $query
                ->select('task,  proyectogo ')->addOrderBy('proyectogo.name', 'ASC')
                ->innerJoin('task.proyectogo', 'proyectogo')
                ->innerJoin('proyectogo.incharge', 'incharge')
                ->where('proyectogo.id = :proyectogo')
                ->setParameter('proyectogo', $proyectogo);
        }elseif($valor === 'analista') {
            $query
                ->select('task,  proyectogo ')->addOrderBy('proyectogo.name', 'ASC')
                ->innerJoin('task.proyectogo', 'proyectogo')
                ->innerJoin('proyectogo.analyst', 'analyst')
                ->where('proyectogo.id = :proyectogo')
                ->setParameter('proyectogo', $proyectogo);

        }

        $entities =  $query->getQuery()->getResult();

        //var_dump($entities);exit;
        return $entities;

    }

    /**
     * @param $proyecto
     * @return \Doctrine\ORM\Query
     */
    public function getDocByProject($proyecto)
    {
        $em = $this->getEntityManager();
         $query =$em->createQueryBuilder();

        return $query
            ->select('doc','task','checker','checkerus', 'assistent', 'assistentus')
            ->from('AppBundle:Document', 'doc')
            ->join('doc.taskdocuments', 'task')
            ->innerJoin('task.proyectogo', 'proyecto')
            ->leftJoin('task.checker', 'checker')
            ->leftJoin('task.assistent', 'assistent')
            ->leftJoin('checker.checker', 'checkerus')
            ->leftJoin('assistent.assistent', 'assistentus')
            ->where('proyecto.id = :proyectogo')

            ->orderBy('doc.root, doc.lft', 'ASC')
            ->setParameter('proyectogo', $proyecto)
        ->getQuery();

        //dump($query);exit;

    }

    /**
     * @param $proyecto
     * @return \Doctrine\ORM\Query
     */
    public function getTaskByProject($proyecto)
    {
        $em = $this->getEntityManager();
        $query =$em->createQueryBuilder();

        return $query
            ->select('task','checker', 'assistent','doc')
            ->from('AppBundle:Taskdocument', 'task')
            ->join('task.proyectogo', 'proyecto')
            ->join('task.document', 'doc')
            ->leftJoin('task.checker', 'checker')
            ->leftJoin('task.assistent', 'assistent')
            ->where('proyecto.id = :proyectogo')
            ->orderBy('doc.root, doc.lft', 'ASC')
            ->setParameter('proyectogo', $proyecto)
            ->getQuery();

        //dump($query);exit;

    }

    /**
     * @param $proyecto
     * @return \Doctrine\ORM\Query
     */
    public function getTask($proyecto)
    {
        $em = $this->getEntityManager();
        $query =$em->createQueryBuilder();

        return $query
            ->select('task','checker', 'assistent','doc')
            ->from('AppBundle:Taskdocument', 'task')
            ->join('task.proyectogo', 'proyecto')
            ->join('task.document', 'doc')
            ->leftJoin('task.checker', 'checker')
            ->leftJoin('task.assistent', 'assistent')
            ->where('proyecto.id = :proyectogo')
            ->orderBy('doc.root, doc.lft', 'ASC')
            ->setParameter('proyectogo', $proyecto)
            ->getQuery();

        //dump($query);exit;

    }

    /**
     * @param $estados
     * @param $user
     * @param $element
     * @param $value
     * @param $role
     * @return \Doctrine\ORM\Query
     */
    public function getTasks($estados, $user,$element, $value, $role)
    {
        $em = $this->getEntityManager();
        $query =$em->createQueryBuilder();
        $estadoFolder = array_search('FOLDER', $estados);
        $estadoPendiente = array_search('PENDIENTE', $estados);
        //dump($estadoFolder);exit();
        $query
            ->select('task','checker', 'assistent','doc')
            ->from('AppBundle:Taskdocument', 'task')
            ->join('task.proyectogo', 'proyecto')
            ->join('task.document', 'doc')
            ->leftJoin('task.checker', 'checker')
            ->leftJoin('checker.checker', 'check')
            ->leftJoin('task.assistent', 'assistent')
            ->join('assistent.assistent','assis')
            ->where('task is not null')
            ->andWhere('task.estado != :estado')
        ;

        if($element == 'idproyecto'){
            $query
                ->andWhere('task.proyectogo = :proyecto');
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
        if($element == null){
            $query
                ->setParameter('estado', $estadoPendiente);
        }else{
            $query
                ->setParameter('estado', $estadoFolder);
        }
        //dump($query);exit;
        return $query
            ->getQuery();
    }

    /**
     * @param $estados
     * @param $user
     * @param $proyecto
     * @param $role
     * @return \Doctrine\ORM\Query
     */
    public function getTasksByProyect($estados, $user, $proyecto, $role)
    {
        $em = $this->getEntityManager();
        $query =$em->createQueryBuilder();
        $estadoFolder = array_search('FOLDER', $estados);

        //dump($estadoFolder);die();
        $query
            ->select('task','checker', 'assistent','doc')
            ->from('AppBundle:Taskdocument', 'task')
            ->join('task.proyectogo', 'proyecto')
            ->join('task.document', 'doc')
            ->leftJoin('task.checker', 'checker')
            ->leftJoin('checker.checker', 'check')
            ->leftJoin('task.assistent', 'assistent')
            ->join('assistent.assistent','assis')
            ->where('task is not null')
            ->andWhere('task.estado != :estado')
            ->andWhere('task.proyectogo = :proyecto');
        ;


        if(in_array('ROLE_ENCARGADO',$role)){
            $query
                ->andWhere('check.id = :user');
        }elseif(in_array('ROLE_ANALISTA',$role)){
            $query
                ->andWhere('assis.id = :user');
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
            ->setParameter('proyecto', $proyecto)
            ->setParameter('estado', $estadoFolder)
            ->getQuery();
    }
}
