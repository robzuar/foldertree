<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Permiso;
use AppBundle\Entity\Usuario;
use AppBundle\Entity\Group;

/**
 * Class PermisoLoadData
 * @package AppBundle\DataFixtures\ORM
 *
 * @author Roberto Zuñiga Araya <roberto.zuniga.araya@gmail.com>
 */
class LoadPermisoData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $grupo = new Permiso();
        $grupo->setName('Grupo Acceso General');

        $grupodos = new Group();
        $grupodos->setName('Grupo Acceso Informática');

        $manager->persist($grupodos);
        $manager->persist($grupo);


        $manager->flush();

        $usuario1 = $this->getUsuario($manager, 'roberto.zuniga.araya@gmail.com');
        $usuario2 = $this->getUsuario($manager, 'mbermudez@imagina.cl');
        $usuario3 = $this->getUsuario($manager, 'coliveros@imagina.cl');

        $usuario1->addPermiso($grupo);
        $usuario2->addPermiso($grupo);
        $usuario3->addPermiso($grupo);

        $proyecto1 = $this->getProyecto($manager, 'Concepto Style');
        $proyecto1->addPermiso($grupo);

        $proyecto2 = $this->getProyecto($manager, 'Fusión');
        $proyecto2->addPermiso($grupo);

        $proyecto3 = $this->getProyecto($manager, 'Información Ventas');
        $proyecto3->addPermiso($grupo);

        $proyecto4 = $this->getProyecto($manager, 'Permiso de Edificación');
        $proyecto4->addPermiso($grupo);

        $proyecto5 = $this->getProyecto($manager, 'Superficie de Ventas');
        $proyecto5->addPermiso($grupo);

        $manager->persist($usuario1);
        $manager->persist($usuario2);
        $manager->persist($usuario3);
        $manager->persist($proyecto1);
        $manager->persist($proyecto2);
        $manager->persist($proyecto3);
        $manager->persist($proyecto4);
        $manager->persist($proyecto5);


        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 7;
    }

    /**
     * @param ObjectManager $manager
     * @param $nombre
     * @return \AppBundle\Entity\Usuario|null|object
     */
    public function getUsuario(ObjectManager $manager, $nombre)
    {
        return $manager->getRepository('AppBundle:Usuario')->findOneBy(array('email' => $nombre));
    }

    /**
     * @param ObjectManager $manager
     * @param $nombre
     * @return \AppBundle\Entity\Proyecto|null|object
     */
    public function getProyecto(ObjectManager $manager, $nombre)
    {
        return $manager->getRepository('AppBundle:Category')->findOneBy(array('title' => $nombre));

    }
}
