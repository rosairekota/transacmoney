<?php

namespace App\Form;

use App\Entity\Retrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RetraitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montantRetire')
            ->add('montantRestant')
            ->add('date_retrait')
            ->add('beneficiaire_piece_type')
            ->add('beneficiaire_piece_image')
            ->add('beneficiaire_piece_numero')
            ->add('libelle')
            ->add('depot')
            ->add('user_retrait')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Retrait::class,
        ]);
    }
}
