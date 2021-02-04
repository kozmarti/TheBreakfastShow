<?php

namespace App\Form;

use App\Entity\Episode;
use App\Form\PreparationType;
use App\Entity\Preparation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EpisodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('season',null, ['choice_label' => 'number'])
            ->add('number')
            ->add('title')
            ->add('recipename')
            ->add('preparationtime')
            ->add('person')
            ->add('preparation', PreparationType::class)
            ->add('images', ImagesType::class);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,


        ]);
    }
}
