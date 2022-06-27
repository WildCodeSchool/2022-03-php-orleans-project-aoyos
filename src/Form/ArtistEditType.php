<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\MusicalStyle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class ArtistEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('birthdate', BirthdayType::class, [
                'label' => 'Date de naissance',
                'format' => 'dd MM yyyy',
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'help' => 'Ceci est votre email de contact, votre email de connexion ne peut pas être modifié.',
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
            ])
            ->add('artistName', TextType::class, [
                'label' => 'Nom d\'artiste',
            ])
            ->add('equipment', TextType::class, [
                'label' => 'Matériel',
            ])
            ->add('musicalStyles', EntityType::class, [
                'class' => MusicalStyle::class,
                'label' => 'Genre musical',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
