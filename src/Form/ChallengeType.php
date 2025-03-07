<?php

namespace App\Form;

use App\Entity\Area;
use App\Entity\Challenge;
use App\Entity\Species;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChallengeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('description')
            ->add('image')
            ->add('hints')
            ->add('area', EntityType::class, [
                'class' => Area::class,
                'choice_label' => 'id',
            ])
            ->add('species', EntityType::class, [
                'class' => Species::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('speciesToGuess', EntityType::class, [
                'class' => Species::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Challenge::class,
        ]);
    }
}
