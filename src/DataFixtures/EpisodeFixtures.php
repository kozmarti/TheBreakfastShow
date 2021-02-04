<?php

namespace App\DataFixtures;

use App\Entity\Season;
use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class EpisodeFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    private $container;
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function getDependencies()

    {

        return [SeasonFixtures::class];

    }

    public function load(ObjectManager $manager)
    {
        $serializer = $this->container->get('serializer');
        $filepath = realpath("./") . "/src/DataFixtures/episode.csv";
        $datas = $serializer->decode(file_get_contents($filepath), 'csv');

        foreach ( $datas as $data) {
            $episode = new Episode();
            $episode->setTitle($data['title']);
            $episode->setNumber((int)$data['number']);
            $episode->setRecipename($data['recipename']);
            $episode->setPreparationtime(new \DateTime($data['preparationtime']));
            $episode->setPerson((int)$data['person']);
            $episode->setSeason($this->getReference('season_' . $data['season_number']));
            $manager->persist($episode);
            $this->addReference('episode_'.$data['title'], $episode);


        }

        $manager->flush();
    }
}
