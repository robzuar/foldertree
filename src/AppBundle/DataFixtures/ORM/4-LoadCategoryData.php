<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use AppBundle\Entity\Category;

/**
 * Class LoadCategoryData
 * @package AppBundle\DataFixtures\ORM
 *
 * @author Roberto Zuñiga Araya <roberto.zuniga.araya@gmail.com>
 */
class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @return int
     */
    public function getOrder()
    {
        return 4;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $proyecto = new Category();
        $proyecto->setTitle('Arquitectura');
        $proyecto->setFirst(true);
        $proyecto->setProyecto(true);
        $carpeta = new Category();
        $carpeta->setTitle('Concepto Style');
        $carpeta->setParent($proyecto);


        $subcarpeta = new Category();
        $subcarpeta->setTitle('Fusión');
        $subcarpeta->setParent($carpeta);

        $subcarpetados = new Category();
        $subcarpetados->setTitle('Información Ventas');
        $subcarpetados->setParent($carpeta);

        $subcarpetatres = new Category();
        $subcarpetatres->setTitle(' Legales - Modificación de Permiso');
        $subcarpetatres->setParent($carpeta);

        $subcarpetacuatro = new Category();
        $subcarpetacuatro->setTitle('Municipales');
        $subcarpetacuatro->setParent($carpeta);

        $subcarpetacinco = new Category();
        $subcarpetacinco->setTitle('Permiso de Edificación');
        $subcarpetacinco->setParent($carpeta);

        $subcarpetaseis = new Category();
        $subcarpetaseis->setTitle('Permisos');
        $subcarpetaseis->setParent($carpeta);

        $subcarpetasiete = new Category();
        $subcarpetasiete->setTitle(' Planos');
        $subcarpetasiete->setParent($carpeta);

        $subcarpetaocho = new Category();
        $subcarpetaocho->setTitle('Superficie de Ventas');
        $subcarpetaocho->setParent($carpeta);

        $carpetados = new Category();
        $carpetados->setTitle('Blest Gana II');
        $carpetados->setParent($proyecto);

        $carpetatres = new Category();
        $carpetatres->setTitle('Colon');
        $carpetatres->setParent($proyecto);

        $carpetacuatro = new Category();
        $carpetacuatro->setTitle('Diagonal Oriente');
        $carpetacuatro->setParent($proyecto);

        $carpetacinco = new Category();
        $carpetacinco->setTitle('Hannover');
        $carpetacinco->setParent($proyecto);

        $carpetaseis = new Category();
        $carpetaseis->setTitle('Marchant Pereira');
        $carpetaseis->setParent($proyecto);

        $carpetasiete = new Category();
        $carpetasiete->setTitle('Mujica Torre 1-2');
        $carpetasiete->setParent($proyecto);

        $carpetaocho = new Category();
        $carpetaocho->setTitle('Mujica Torre 3');
        $carpetaocho->setParent($proyecto);

        $carpetanueve = new Category();
        $carpetanueve->setTitle('Nibelungos');
        $carpetanueve->setParent($proyecto);

        $carpetadiez = new Category();
        $carpetadiez->setTitle('Simon Bolivar 2');
        $carpetadiez->setParent($proyecto);

        $carpetaonce = new Category();
        $carpetaonce->setTitle('Sucre');
        $carpetaonce->setParent($proyecto);

        $manager->persist($proyecto);
        $manager->persist($carpeta);
        $manager->persist($carpetados);
        $manager->persist($carpetatres);
        $manager->persist($carpetacuatro);
        $manager->persist($carpetacinco);
        $manager->persist($carpetaseis);
        $manager->persist($carpetasiete);
        $manager->persist($carpetaocho);
        $manager->persist($carpetanueve);
        $manager->persist($carpetadiez);
        $manager->persist($carpetaonce);
        $manager->persist($subcarpeta);
        $manager->persist($subcarpetados);
        $manager->persist($subcarpetatres);
        $manager->persist($subcarpetacuatro);
        $manager->persist($subcarpetacinco);
        $manager->persist($subcarpetaseis);
        $manager->persist($subcarpetasiete);
        $manager->persist($subcarpetaocho);


        $manager->flush();
    }


}
