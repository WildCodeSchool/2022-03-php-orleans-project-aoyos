<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
            'label' => 'Prénom',
            'attr' => ['placeholder' => 'Gaëtan'],
            'constraints' => [new Length(['max' => 255]), new NotBlank()],
            ])
            ->add('lastname', TextType::class, ['label' => 'Nom',
            'attr' => ['placeholder' => 'Laurent'],
            'constraints' => [new Length(['max' => 255]), new NotBlank()],
            ])
            ->add('birthdate', BirthdayType::class, ['label' => 'Date de naissance',
            'years' => range(date('Y') - 15, (date('1940'))),
            'constraints' => [new NotBlank()],
            ])
            ->add('phone', TextType::class, [
            'label' => 'Téléphone',
            'attr' => ['placeholder' => '01 70 06 05 70'],
            'constraints' => [new Length(['max' => 255]), new NotBlank()],
            ])
            ->add('email', EmailType::class, [
            'label' => 'Email',
            'attr' => ['placeholder' => 'hello@aoyos.fr'],
            'constraints' => [new Length(['max' => 255]), new NotBlank(), new Email()],
            ])
            ->add('address', TextType::class, [
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
