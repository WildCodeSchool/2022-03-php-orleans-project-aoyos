<?php

namespace App\Form;

use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('identityCardFile', VichImageType::class, [
                'label' => 'Carte d\'identité',
                'download_uri' => false,
                'allow_delete' => false,
            ])
            ->add('identityPhotoFile', VichImageType::class, [
                'label' => 'Photo d\'identité',
                'download_uri' => false,
                'allow_delete' => false,
            ])
            ->add('kbisFile', VichImageType::class, [
                'label' => 'KBIS',
                'required' => false,
                'download_uri' => false,
                'allow_delete' => false,
            ])
            ->add('siretNumber', TextType::class, [
                'label' => 'SIRET',
                'attr' => ['placeholder' => '802 954 785 00028'],
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
