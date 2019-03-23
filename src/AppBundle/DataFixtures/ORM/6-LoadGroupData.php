<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\Group;

/**
 * Class GroupLoadData
 * @package AppBundle\DataFixtures\ORM
 *
 * @author Roberto Zuñiga Araya <roberto.zuniga.araya@gmail.com>
 */
class LoadGroupData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

        $grupo = new Group();
        $grupo->setName('Grupo General');

        $grupodos = new Group();
        $grupodos->setName('Grupo Informática');

        $manager->persist($grupodos);
        $manager->persist($grupo);


        $manager->flush();

        $usuario1 = $this->getUsuario($manager, 'roberto.zuniga.araya@gmail.com');
        $usuario2 = $this->getUsuario($manager, 'mbermudez@imagina.cl');
        $usuario3 = $this->getUsuario($manager, 'coliveros@imagina.cl');

        $usuario1->addGroup($grupo);
        $usuario2->addGroup($grupo);
        $usuario3->addGroup($grupo);


        $manager->persist($usuario1);
        $manager->persist($usuario2);
        $manager->persist($usuario3);


        $manager->flush();

    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 6;
    }

    /**
     * @param ObjectManager $manager
     * @param $nombre
     * @return \AppBundle\Entity\Usuario|null|object
     */
    public function getUsuario(ObjectManager $manager, $nombre)
    {
        return $manager->getRepository('AppBundle:Usuario')->findOneBy(array('email'=>$nombre));
    }
}
