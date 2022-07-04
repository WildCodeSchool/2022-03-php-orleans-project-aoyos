<?php

namespace App\Form;

use App\Config\ReservationStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchAdminReservationType extends AbstractType
{
    private array $reservationStatus;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach (ReservationStatus::cases() as $case) {
            $this->reservationStatus[$case->value] = $case->name;
        }

        $builder
            ->setMethod('GET')
            ->add('search', SearchType::class, [
                'label' => 'Nom de l\'événement',
                'required' => false
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'required' => false,
                'choices' => $this->reservationStatus,
                'attr' => [
                    'onchange' => 'document.forms["search_admin_reservation"].submit()'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
