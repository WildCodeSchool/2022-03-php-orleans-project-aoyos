<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', null, [
            'label' => 'Prénom',
            'attr' => ['placeholder' => 'Gaëtan'],
            'constraints' => [new Length(['max' => 255]), new NotBlank()],
            ])
            ->add('lastname', null, ['label' => 'Nom',
            'attr' => ['placeholder' => 'Laurent'],
            'constraints' => [new Length(['max' => 255]), new NotBlank()],
            ])
            ->add('birthdate', null, ['label' => 'Date de naissance',
            'years' => range(date('Y') - 15, (date('1940'))),
            'constraints' => [new NotBlank()],
            ])
            ->add('phone', null, [
            'label' => 'Téléphone',
            'attr' => ['placeholder' => '01 70 06 05 70'],
            'constraints' => [new Length(['max' => 255]), new NotBlank()],
            ])
            ->add('email', null, [
            'label' => 'Email',
            'attr' => ['placeholder' => 'hello@aoyos.fr'],
            'constraints' => [new Length(['max' => 255]), new NotBlank(), new Email()],
            ])
            ->add('address', null, [
                'label' => 'Adresse',
                'attr' => ['placeholder' => '15 rue des Halles, 75 001 Paris'],
                'constraints' => [new Length(['max' => 255]), new NotBlank()],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
