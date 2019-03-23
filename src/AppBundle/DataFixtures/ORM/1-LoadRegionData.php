<?php
namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use AppBundle\Entity\Region;

/**
 * Class LoadRegionData
 * @package AppBundle\DataFixtures\ORM
 *
 * @author Roberto Zuñiga Araya <roberto.zuniga.araya@gmail.com>
 */
class LoadRegionData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $regions = $this->getRegions();

        foreach ($regions as $region) {
            $newRegion = new Region();
            $newRegion->setName($region['name']);
            $newRegion->setCode($region['code']);
            $manager->persist($newRegion);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    private function getRegions()
    {
        return [
            ['name' => 'Arica y Parinacota', 'code' => 'XV'],
            ['name' => 'Tarapacá', 'code' => 'I'],
            ['name' => 'Antofagasta', 'code' => 'II'],
            ['name' => 'Atacama', 'code' => 'III'],
            ['name' => 'Coquimbo', 'code' => 'IV'],
            ['name' => 'Valparaiso', 'code' => 'V'],
            ['name' => 'Metropolitana de Santiago', 'code' => 'RM'],
            ['name' => 'Libertador General Bernardo O\'Higgins', 'code' => 'VI'],
            ['name' => 'Maule', 'code' => 'VII'],
            ['name' => 'Biobío', 'code' => 'VIII'],
            ['name' => 'La Araucanía', 'code' => 'IX'],
            ['name' => 'Los Ríos', 'code' => 'XIV'],
            ['name' => 'Los Lagos', 'code' => 'X'],
            ['name' => 'Aisén del General Carlos Ibáñez del Campo', 'code' => 'XI'],
            ['name' => 'Magallanes y de la Antártica Chilena', 'code' => 'XII'],
        ];
    }
}
