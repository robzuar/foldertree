<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Proyecto;

/**
 * Class ProyectoLoadData
 * @package AppBundle\DataFixtures\ORM
 *
 * @author Roberto Zuñiga Araya <roberto.zuniga.araya@gmail.com>
 */
class LoadProyectoData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $proyecto = new proyecto();

        $proyecto->setNombre('Concepto Style');
        $proyecto->setInmobiliaria('Inmobiliaria Los Quillayes SA');
        $proyecto->setDireccion('1 Oriente 132, Viña del Mar');
        $proyecto->setRut('76.216.556-2');
        $proyecto->setBodegas(1);
        $proyecto->setDepartamentos(68);
        $proyecto->setEstacionamientos(2);
        $proyecto->setPisos(15);
        $proyecto->setSubterraneos(2);
        $proyecto->setDateend(new \DateTime());
        $proyecto->setDateescritura(new \DateTime());
        $proyecto->setArquitecto('Juan Ignacio Lobo');
        $proyecto->setSupervisor('Felipe Montalvo');
        $proyecto->setEstado('Desarrollo Arquitectura');
        $proyecto->setDatedelivery('Primer Semestre 2018');
        $proyecto->setEnabled(true);
        $proyecto->setCategory($this->getFolder($manager,'Concepto Style'));

        $manager->persist($proyecto);


        $manager->flush();



    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 5;
    }

    /**
     * @param ObjectManager $manager
     * @param $nombre
     * @return \AppBundle\Entity\Category|null|object
     */
    public function getFolder(ObjectManager $manager, $nombre)
    {
        return $manager->getRepository('AppBundle:Category')->findOneBy(array('title'=>$nombre));
    }
}
