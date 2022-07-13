<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
            'label' => 'Prénom',
            'attr' => ['placeholder' => 'Gaëtan'],
            ])
            ->add('lastname', TextType::class, ['label' => 'Nom',
            'attr' => ['placeholder' => 'Laurent'],
            ])
            ->add('birthdate', BirthdayType::class, ['label' => 'Date de naissance',
            'years' => range(date('Y') - 15, (date('1940'))),
            ])
            ->add('phone', TextType::class, [
            'label' => 'Téléphone',
            'attr' => ['placeholder' => '01 70 06 05 70'],
            ])
            ->add('address', TextType::class, [
            'label' => 'Adresse',
            'attr' => ['placeholder' => '15 rue des Halles, 75 001 Paris'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
            'validation_groups' => ['djInfos'],
        ]);
    }
}
