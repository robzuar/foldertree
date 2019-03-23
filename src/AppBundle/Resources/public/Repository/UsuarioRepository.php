<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class UsuarioRepository
 *
 * @package AppBundle\EntityRepository
 */
class UsuarioRepository extends EntityRepository
{
    /**
     * @param $role
     * @return mixed
     */
    public function getUserByRole($role)
    {
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Usuario');

        $query = $repository->createQueryBuilder('c');


            $query
                ->select('c')
                ->where('c.roles like :role')
                ->setParameter('role', '%"'.$role.'"%');

            return $query->getQuery()->getResult();

    }

    /**
     * @param $group
     * @return array
     */
    public function getUserGroups($group)
    {
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Usuario');

        $query = $repository->createQueryBuilder('usuario');

        if($group->getId()) {
            $query
                ->join("usuario.groups", "g")
                ->where('g.id = :group')
                ->setParameter('group', $group->getId());

            return $query->getQuery()->getResult();
        }else{
            return null;
        }
    }


    /**
     * @param $acceso
     * @return array
     */
    public function getUserAccesos($acceso)
    {
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Usuario');

        $query = $repository->createQueryBuilder('usuario');

        if(count($acceso) >= 1) {
            $query
                ->join("usuario.accesos", "g")
                ->where('g.id = :acceso')
                ->setParameter('acceso', $acceso->getId());

            return $query->getQuery()->getResult();
        }else{
            return null;
        }
    }

    /**
     * @param $permisos
     * @return array
     */
    public function getUserPermisos($permisos)
    {
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Usuario');

        $query = $repository->createQueryBuilder('usuario');

        if($permisos->getId()) {
            $query
                ->join("usuario.permisos", "g")
                ->where('g.id = :permiso')
                ->setParameter('permiso', $permisos->getId());

            return $query->getQuery()->getResult();
        }else{
            return null;
        }
    }

    /**
     * @param $group
     * @return array
     */
    public function getUserGrupoCambios($group)
    {

        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Usuario');

        $query = $repository->createQueryBuilder('usuario');

        if($group->getId()) {
            $query
                ->join("usuario.grupocambios", "g")
                ->where('g.id = :group')
                ->setParameter('group', $group->getId());

            return $query->getQuery()->getResult();
        }else{
            return null;
        }
    }

    /**
     * @param $group
     * @return array
     */
    public function getUserCambios($group)
    {
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Usuario');

        $query = $repository->createQueryBuilder('usuario');

        if($group->getId()) {
            $query
                ->join("usuario.grupocambios", "g")
                ->where('g.id = :group')
                ->setParameter('group', $group->getId());

            return $query->getQuery()->getResult();
        }else{
            return null;
        }
    }


    /**
     * @param 
     * @return array
     */
    public function getUserGrupoAnteproyecto($group)
    {
        //var_dump($group);exit;
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Usuario');

        $query = $repository->createQueryBuilder('usuario');

        if($group->getId()) {
            $query
                ->join("usuario.grupoanteproyecto", "g")
                ->where('g.id = :group')
                ->setParameter('group', $group->getId());

            return $query->getQuery()->getResult();
        }else{
            return null;
        }
    }

    /**
     * @param $group
     * @return array
     */
    public function getUserAnteproyecto($group)
    {
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Usuario');

        $query = $repository->createQueryBuilder('usuario');

        if($group->getId()) {
            $query
                ->join("usuario.grupoanteproyecto", "g")
                ->where('g.id = :group')
                ->setParameter('group', $group->getId());

            return $query->getQuery()->getResult();
        }else{
            return null;
        }
    }
}
