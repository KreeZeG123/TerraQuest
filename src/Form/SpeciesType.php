<?php

namespace App\Form;

use App\Entity\Area;
use App\Entity\Species;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;

class SpeciesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('latinName')
            ->add('commonName')
            ->add('slug', TextType::class , [
                'attr' => ['readonly' => true],
            ])
            ->add('origin')
            ->add('characteristics')
            ->add('utility')
            ->add('cultivationCondition')
            ->add('images')
            ->add('latGPS')
            ->add('lngGPS')
            ->add('area', EntityType::class, [
                'class' => Area::class,
                'choice_label' => 'id',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Envoyer',
            ])
            // Ajoute l'événement PRE_SUBMIT pour générer le slug automatiquement
            ->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'autoSlug']);
    }

    public function autoSlug(PreSubmitEvent $event): void
    {
        $data = $event->getData();


        // Si le slug n'est pas défini et si latinName a changé, générer un nouveau slug
        if (!empty($data['latinName'])) {
            $slugger = new AsciiSlugger();
            // Générer un slug basé sur latinName
            $data['slug'] = strtolower($slugger->slug($data['latinName']));
            // Mise à jour des données
            $event->setData($data);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Species::class,
        ]);
    }
}
