<?php

namespace App\DataFixtures;

use App\Entity\ListIngredient;
use App\Entity\Episode;
use App\Entity\Measure;
use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ListIngredientFixtures extends Fixture implements ContainerAwareInterface, DependentFixtureInterface
{
    private $container;
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function getDependencies()

    {

        return [EpisodeFixtures::class, MeasureFixtures::class, IngredientFixtures::class, ];

    }

    public function load(ObjectManager $manager)
    {
        $serializer = $this->container->get('serializer');
        $filepath = realpath("./") . "/src/DataFixtures/listingredient.csv";
        $datas = $serializer->decode(file_get_contents($filepath), 'csv');

        foreach ( $datas as $data) {
            $list = new ListIngredient();
            if(round((float)$data['quantity'],2)!= 0){
                $list->setQuantity(round((float)$data['quantity'],2));
            }
            $list->setSubIngredient($data['subingredient']);

            $list->setEpisode($this->getReference('episode_' . $data['episode_title']));
            $list->setIngredient($this->getReference('ingredient_' . $data['ingredient_name']));
            if (!empty( $data['measure_name'])){
                $list->setMeasure($this->getReference('measure_' . $data['measure_name']));
            }
            $manager->persist($list);


        }

        $manager->flush();
    }
}
