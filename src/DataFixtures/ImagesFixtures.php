<?php

namespace App\DataFixtures;

use App\Entity\Images;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ImagesFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    private $container;
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function getDependencies()

    {

        return [EpisodeFixtures::class];

    }

    public function load(ObjectManager $manager)
    {
        $serializer = $this->container->get('serializer');
        $filepath = realpath("./") . "/src/DataFixtures/images.csv";
        $datas = $serializer->decode(file_get_contents($filepath), 'csv');

        foreach ( $datas as $data) {
            $image = new Images();
            $image->setMeal($data['meal']);
            $image->setIngredient($data['ingredient']);
            $image->setOwnerphoto($data['ownerphoto']);
            $image->setEpisode($this->getReference('episode_' . $data['episode_title']));
            $manager->persist($image);


        }

        $manager->flush();
    }
}