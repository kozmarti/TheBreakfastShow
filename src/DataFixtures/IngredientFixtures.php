<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class IngredientFixtures extends Fixture implements ContainerAwareInterface
{
    private $container;
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $serializer = $this->container->get('serializer');
        $filepath = realpath("./") . "/src/DataFixtures/ingredient.csv";
        $datas = $serializer->decode(file_get_contents($filepath), 'csv');

        foreach ( $datas as $data) {
            $ingredient = new Ingredient();
            $ingredient->setName($data['name']);
            $manager->persist($ingredient);
            $this->addReference('ingredient_'.$data['name'], $ingredient);
        }

        $manager->flush();
    }
}
