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
            ->add('montantRetire',null,['attr'=>[
                'readonly'=>true
            ]])
            ->add('code_retrait',null,['attr'=>[
                'readonly'=>true
            ]])
            ->add('beneficiaire_piece_type',null,[
                'label' =>"Type de la pièce d'identité",
                'attr'=>[
                'placeholder'=>"Ex: Carte d'Electeur"
            ]])
            ->add('beneficiaire_piece_numero',null,[
                'label' =>"Numéro de la pièce d'identité",
                'attr'=>[
                'placeholder'=>"Ex: 4012 0010 38"
            ]])
            ->add('libelle')
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Retrait::class,
        ]);
    }
}
