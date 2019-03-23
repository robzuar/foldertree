<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class ComunaRepository
 *
 * @package AppBundle\EntityRepository
 *
 * @author Roberto Zuñiga Araya <roberto.zuniga.araya@gmail.com>
 */
class ComunaRepository extends EntityRepository
{
/*
    /**
     * @param $idColegio
     * @param $idCurso
     * @param $idPrograma
     * @return array
     */
    /*
    public function findAlumnosPorColegioCursoPrograma($idColegio, $idCurso, $idPrograma)
    {
        $query = $this
            ->createQueryBuilder('alumno')
            ->innerJoin('alumno.curso', 'curso')
            ->innerJoin('curso.colegio', 'colegio')
            ->innerJoin('alumno.flujoProgramas', 'flujoProgramas')
            ->innerJoin('flujoProgramas.programa', 'programa')
            ->andWhere('curso.id = :curso')
            ->andWhere('colegio.id = :colegio')
            ->andWhere('programa.id = :programa')
            ->andWhere('flujoProgramas.fechaEgreso is null')
            ->setParameter('curso', $idCurso)
            ->setParameter('colegio', $idColegio)
            ->setParameter('programa', $idPrograma)
        ;

        return $query
            ->getQuery()
            ->getResult()
            ;
    }
*/
}
