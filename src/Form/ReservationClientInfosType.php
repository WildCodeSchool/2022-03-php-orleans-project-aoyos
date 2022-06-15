<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ReservationClientInfosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'constraints' => [new Assert\Length(['max' => 255])],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [new Assert\Length(['max' => 255])],
            ])
            ->add('company', TextType::class, [
                'label' => 'Société',
                'constraints' => [new Assert\Length(['max' => 255])],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [new Assert\Email()],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'constraints' => [new Assert\Length(['max' => 255])],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
