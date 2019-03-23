<?php
namespace AppBundle\Repository;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * Class CategoryRepository
 * @package AppBundle\Repository
 */
class CategoryRepository extends NestedTreeRepository
{

    /**
     * @return array
     */
    public function findFirstMenu()
    {
        $query = $this
            ->createQueryBuilder('category')
            ->andWhere('category.parent is null')
            ->andWhere('category.first = 1')

        ;

        return $query
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param $idMenuPrincipal
     * @return array
     */
    public function secondlevelMenu($idMenuPrincipal)
    {

        $query = $this
            ->createQueryBuilder('category')
            ->andWhere('category.parent = :identificador')
            ->setParameter('identificador', $idMenuPrincipal)

        ;

        return $query
            ->getQuery()
            ->getResult()
            ;

    }

    /**
     * @param $userid
     * @param $idcategory
     * @return array
     */
    public function getCategoryPermisoByUser($userid, $idcategory){

        $idfiles = [];
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Usuario');

        $query = $repository->createQueryBuilder('u');

        $query
            ->select("a.id")
            ->join("u.permisos", "a")
            ->where('u.id = :usuario')
            ->setParameter('usuario', $userid);

        $usuarios =  $query->getQuery()->getResult();

        $repository2 = $this->getEntityManager()
            ->getRepository('AppBundle:Category');
        $query2 = $repository2->createQueryBuilder('category');
        //echo $userid;die();
//var_dump($usuarios);die();
        $ids = "";
        $counter = 1;
        foreach ($usuarios as $usuario){
            if($counter == 1) {
                $ids = '('.$usuario['id'];
            }else{
                $ids = $ids .',' .$usuario['id'];
            }
            $counter++;
        }

        if($ids != "") {
            $ids = $ids . ')';
        }
        //var_dump($ids);die();


        if($ids != "") {
            return $query2
                ->select('category.id')
                ->join("category.permisos", "p")
                ->Where('category.id = :idcategory')
                ->andwhere('p.id in ' . $ids . '')
                ->setParameter('idcategory', $idcategory)
                ->getQuery()
                ->getResult();
        }else{
            return null;
        }
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $query = $this
            ->createQueryBuilder('category')
            ->andWhere('category.enabled = 1')
            ->orderBy('category.root, category.lft', 'ASC')
            ->getQuery()

        ;

    }

    /**
     * @param $usuario
     * @return array
     */
    public function getResponsableMenu($usuario)
    {
        $repository2 = $this->getEntityManager()
            ->getRepository('AppBundle:Category');
        $query = $repository2->createQueryBuilder('category');

        return $query
            ->select('category.id')
            ->join("category.encargado", "p")
            ->andWhere('p.id = :usuario')
            ->setParameter('usuario', $usuario)
            ->getQuery()
            ->getResult();
            ;
    }
}