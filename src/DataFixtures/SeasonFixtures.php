<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SeasonFixtures extends Fixture implements ContainerAwareInterface
{
    private $container;
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function load(ObjectManager $manager)
    {
        $serializer = $this->container->get('serializer');
        $filepath = realpath("./") . "/src/DataFixtures/season.csv";
        $datas = $serializer->decode(file_get_contents($filepath), 'csv');
        $i=1;
        foreach ( $datas as $key=>$data) {
            $season = new Season();
            $season->setTitle($data['title']);
            $season->setNumber((int)$data['number']);
            $season->setSlug('season-'.$data['number'].'-'.$data['title']);
            $manager->persist($season);
            $this->addReference('season_'.$i, $season);
            $i++;
        }


        $manager->flush();
    }
}
