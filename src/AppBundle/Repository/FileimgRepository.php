<?php
namespace AppBundle\Repository;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * Class FileimgRepository
 * @package AppBundle\Repository
 */
class FileimgRepository extends NestedTreeRepository
{
    /**
     * @param $id
     * @return array
     */
    public function getTheTop($id)
    {
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Fileimg');

        $query =
            $repository
                ->createQueryBuilder('fileimg');
        $query
            ->select( 'max(fileimg.level) AS top', 'fileimg.id',' fileimg.link',' fileimg.created')
            ->join('fileimg.category', 'category', \Doctrine\ORM\Query\Expr\Join::WITH,
                'fileimg.category = category.id')
            ->join('fileimg.createdby', 'usuario', \Doctrine\ORM\Query\Expr\Join::WITH,
                'fileimg.createdby = usuario.id')
            ->where(' fileimg.category = :id')
            ->groupBy('fileimg.root')
            ->setParameter('id', $id)
        ;

        return $query
            ->getQuery()
            ->getResult()
            ;
    }

    public function querytop($id){

        $em = $this->getEntityManager();
        $query = $em->createQuery
        (
          '
          SELECT fileimg, MAX(fileimg.level) as maximo
          FROM AppBundle:Fileimg fileimg
          JOIN fileimg.category categoria
          JOIN fileimg.createdby usuario
          WHERE 1=1
          AND  fileimg.category = :id
          GROUP BY fileimg.root, fileimg.id
          '
        );

        $query->setParameter('id', $id);

        return $query->getResult();
    }

    /**
     * @param $fileimg
     * @return array
     */
    public function getFileimgAccesos($fileimg)
    {
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Fileimg');

        $query = $repository->createQueryBuilder('fileimg');

        if(count($fileimg) >= 1) {
            $query
                ->join("fileimg.accesos", "g")
                ->where('fileimg.id = :fileimg')
                ->setParameter('fileimg', $fileimg->getId());

            return $query->getQuery()->getResult();
        }else{
            return null;
        }
    }

    /**
     * @param $userid
     * @return array|null
     */
    public function getFileimgAccesosUsuarios($userid)
    {
        $idfiles = [];
        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Usuario');

        $query = $repository->createQueryBuilder('u');

        $query
            ->select("a.id")
            ->join("u.accesos", "a")
            ->where('u.id = :usuario')
            ->setParameter('usuario', $userid);

        $usuarios =  $query->getQuery()->getResult();

        $repository = $this->getEntityManager()
            ->getRepository('AppBundle:Fileimg');
        $query = $repository->createQueryBuilder('f');

        foreach($usuarios as $id) {


            $query
                ->select('f.id')
                ->join("f.accesos", "a")
                ->where('a.id = :id')
                ->setParameter('id', $id['id']);

            $idfiles[] = $query->getQuery()->getResult();
        }
        return $idfiles;
    }

}