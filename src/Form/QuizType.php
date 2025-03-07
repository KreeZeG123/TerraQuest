<?php

namespace App\Form;

use App\Entity\Quiz;
use App\Entity\Species;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question')
            ->add('answers', CollectionType::class, [
                'entry_type' => TextType::class,
                'allow_add' => false, // On empêche l'ajout dynamique
                'allow_delete' => false, // On empêche la suppression dynamique
                'prototype' => false, // Désactive le prototype pour ne pas avoir d'ajout JS
                'label' => 'Choix',
                'entry_options' => [
                    'attr' => ['class' => 'answer-field'],
                ],
                'data' => array_fill(0, 4, '') // Remplit avec 4 champs vides par défaut
            ])
            ->add('correctAnswer', TextType::class, [
                'label' => "Réponse"
            ])
            ->add('species', EntityType::class, [
                'class' => Species::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
