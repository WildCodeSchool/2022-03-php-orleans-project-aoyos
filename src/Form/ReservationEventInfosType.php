<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationEventInfosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('formula', ChoiceType::class, [
                'label' => 'Formule',
                'choices'  => [
                    'Solo' => 'Solo',
                    'Standard' => 'Standard',
                    'Sur mesure' => 'Sur mesure'
                ]
            ])
            ->add('eventType', TextType::class, [
                'label' => 'Type d\'évènement',
                'attr' => ['placeholder' => 'Lancement de produit'],
            ])
            ->add('address', TextType::class, [
                'label' => 'Lieu',
                'attr' => ['placeholder' => '15 rue des halles, 75001 Paris'],
            ])
            ->add('dateStart', DateTimeType::class, [
                'label' => 'Début',
            ])
            ->add('dateEnd', DateTimeType::class, [
                'label' => 'Fin'
            ])
            ->add('attendees', IntegerType::class, [
                'label' => 'Participants',
                'attr' => ['placeholder' => '150'],
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire (optionnel)',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'validation_groups' => ['eventInfos'],
        ]);
    }
}
