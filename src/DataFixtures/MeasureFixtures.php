<?php

namespace App\DataFixtures;

use App\Entity\Measure;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MeasureFixtures extends Fixture implements ContainerAwareInterface
{
    private $container;
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $serializer = $this->container->get('serializer');
        $filepath = realpath("./") . "/src/DataFixtures/measure.csv";
        $datas = $serializer->decode(file_get_contents($filepath), 'csv');

        foreach ( $datas as $data) {
            $measure = new Measure();
            $measure->setName($data['name']);
            $manager->persist($measure);
            $this->addReference('measure_'.$data['name'], $measure);
        }

        $manager->flush();
    }
}
