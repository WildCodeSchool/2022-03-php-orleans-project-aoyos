<?php

namespace App\Form;

use App\Config\ReservationStatus;
use App\Entity\MusicalStyle;
use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    private array $reservationStatus;
    public const FORMULAS = [
        'Solo' => 'Solo',
        'Standard' => 'Standard',
        'Sur mesure' => 'Sur mesure'
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach (ReservationStatus::cases() as $case) {
            $this->reservationStatus[$case->value] = $case->name;
        }

        $builder
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => $this->reservationStatus,
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom'
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom'
            ])
            ->add('company', TextType::class, [
                'label' => 'Société'
            ])
            ->add('email', EmailType::class)
            ->add('phone', TextType::class, [
                'label' => 'Téléphone'
            ])
            ->add('formula', ChoiceType::class, [
                'label' => 'Formule',
                'choices'  => self::FORMULAS,
            ])
            ->add('eventType', TextType::class, [
                'label' => 'Type d\'évènement'
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse'
            ])
            ->add('dateStart', DateTimeType::class, [
                'label' => 'Début',
                'widget' => 'single_text',
            ])
            ->add('dateEnd', DateTimeType::class, [
                'label' => 'Fin',
                'widget' => 'single_text',
            ])
            ->add('attendees', NumberType::class, [
                'label' => 'Participants'
            ])
            ->add('commentClient', TextareaType::class, [
                'label' => 'Commentaire client (non visible côté DJ)',
                'required' => false,
            ])
            ->add('musicalStyles', EntityType::class, [
                'class' => MusicalStyle::class,
                'label' => 'Genre musical',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
            ])
            ->add('commentAdmin', TextareaType::class, [
                'label' => 'Commentaire admin (visible côté DJ)',
                'required' => false
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Tarif',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
