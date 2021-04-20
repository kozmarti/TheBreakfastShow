<?php

namespace App\DataFixtures;

use App\Entity\FunFact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FunFactFixtures extends Fixture implements ContainerAwareInterface
{
    private $container;
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $serializer = $this->container->get('serializer');
        $filepath = realpath("./") . "/src/DataFixtures/funfact.csv";
        $datas = $serializer->decode(file_get_contents($filepath), 'csv');

        foreach ( $datas as $data) {
            $measure = new FunFact();
            $measure->setName(substr((substr($data['image'],22)),0,-4));
            $measure->setImage($data['image']);
            $manager->persist($measure);
        }

        $manager->flush();
    }
}