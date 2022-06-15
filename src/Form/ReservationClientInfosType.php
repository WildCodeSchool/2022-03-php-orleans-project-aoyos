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
                'attr' => ['placeholder' => 'Dupont'],
                'constraints' => [new Assert\Length(['max' => 255]), new Assert\NotBlank()],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Jean'],
                'constraints' => [new Assert\Length(['max' => 255]), new Assert\NotBlank()],
            ])
            ->add('company', TextType::class, [
                'label' => 'Société',
                'attr' => ['placeholder' => 'aoyos'],
                'constraints' => [new Assert\Length(['max' => 255]), new Assert\NotBlank()],
            ])
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => 'aoyos@aoyos.fr'],
                'constraints' => [new Assert\Email(), new Assert\NotBlank()],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'attr' => ['placeholder' => '06 99 99 99 99'],
                'constraints' => [new Assert\Length(['max' => 255]), new Assert\NotBlank()],
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
