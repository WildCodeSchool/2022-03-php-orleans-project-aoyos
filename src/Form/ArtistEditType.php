<?php

namespace App\Form;

use App\Entity\Artist;
use App\Form\ArtistProfileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('artist', ArtistType::class, [
                'data_class' => Artist::class,
                'label' => false
            ])
            ->add('profile', ArtistProfileType::class, [
                'data_class' => Artist::class,
                'label' => false
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
