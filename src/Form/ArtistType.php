<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', null, [
            'label' => 'Prénom',
            'attr' => ['placeholder' => 'Gaëtan']
            ])
            ->add('lastname', null, ['label' => 'Nom',
            'attr' => ['placeholder' => 'Laurent']
            ])
            ->add('artistname', null, ['label' => 'Nom d\'artiste',
            'attr' => ['placeholder' => 'Gaëtan']
            ])
            ->add('birthdate', null, ['label' => 'Date de naissance',
            'years' => range(date('Y') - 15, (date('1940'))),
            ])
            ->add('idcard', null, ['label' => 'Carte d\'identité'])
            ->add('idphoto', null, ['label' => 'Photo d\'identité'])
            ->add('phone', null, [
            'label' => 'Téléphone',
            'attr' => ['placeholder' => '01 70 06 05 70']
            ])
            ->add('mail', null, [
            'label' => 'Email',
            'attr' => ['placeholder' => 'hello@aoyos.fr']
            ])
            ->add('address', null, [
                'label' => 'Adresse',
                'attr' => ['placeholder' => '15 rue des Halles, 75 001 Paris']
                ])
            ->add('siret', null, [
                'label' => 'SIRET',
                'attr' => ['placeholder' => '123 568 941 00056']
                ])
            ->add('kbis', null, ['label' => 'KBIS'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
        ]);
    }
}
