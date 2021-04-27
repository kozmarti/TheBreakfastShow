<?php

namespace App\Form;

use App\Entity\Episode;
use App\Entity\Images;
use App\Entity\Season;
use App\Form\PreparationType;
use App\Entity\Preparation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('season',EntityType::class, [
                'class'=> Season::class,
                'choice_label' => function(Season $season) {
                return 'Season ' . $season->getNumber() . '.: ' . $season->getTitle();
            }
                ])
            ->add('title')
            ->add('recipename')
            ->add('preparationtime')
            ->add('person')
            ->add('preparation', PreparationType::class)
            ->add('images', ImagesType::class);
            /*->add('images', ImagesType::class);*/


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,


        ]);
    }
}
