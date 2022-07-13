<?php

namespace App\Form;

use App\Entity\Artist;
use App\Entity\MusicalStyle;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistProfileType extends AbstractType
{
    public const EQUIPMENT_CHOICE = [
        'Platines et contrôleur' => 'Platines et contrôleur',
        'Platines, contrôleur et sonorisation' => 'Platines, contrôleur et sonorisation',
        'Platines, contrôleur, sonorisation et lumière' => 'Platines, contrôleur, sonorisation et lumière'
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('artistName', TextType::class, [
                'label' => 'Nom d\'artiste',
                'attr' => ['placeholder' => 'aoyos'],
                ])
            ->add('equipment', ChoiceType::class, [
                'label' => 'Matériel',
                'choices'  => self::EQUIPMENT_CHOICE,
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('musicalStyles', EntityType::class, [
                'class' => MusicalStyle::class,
                'label' => 'Genre musical',
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'by_reference' => false,
                'attr' => [
                    'class' => 'musical-styles'
                ],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.name', 'ASC');
                },
                ])
            ->add('message', TextType::class, [
                'label' => 'Message (optionnel)',
                'attr' => ['placeholder' => 'Informations supplémentaires'],
                'required' => false,
                ])
            ->add('instagram', UrlType::class, [
                'label' => 'Lien Instagram (optionnel)',
                'required' => false
            ])
            ->add('soundCloud', UrlType::class, [
                'label' => 'Lien SoundCloud (optionnel)',
                'required' => false
            ])
            ->add('facebook', UrlType::class, [
                'label' => 'Lien Facebook (optionnel)',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Artist::class,
            'validation_groups' => ['djProfile'],
        ]);
    }
}
