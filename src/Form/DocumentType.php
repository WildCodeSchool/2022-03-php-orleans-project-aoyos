<?php

namespace App\Form;

use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('identityCardFile', VichFileType::class, [
                'label' => 'Carte d\'identité',
                'required' => false,
                'download_label' => 'Voir le document'
            ])
            ->add('identityPhotoFile', VichFileType::class, [
                'label' => 'Photo d\'identité',
                'required' => false,
                'download_label' => 'Voir le document'
            ])
            ->add('kbisFile', VichFileType::class, [
                'label' => 'KBIS',
                'required' => false,
                'download_label' => 'Voir le document'
            ])
            ->add('siretNumber', TextType::class, [
                'label' => 'SIRET',
                'required' => false,
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
