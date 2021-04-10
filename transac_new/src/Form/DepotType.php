<?php

namespace App\Form;

use App\Entity\Beneficiaire;
use App\Entity\City;
use App\Entity\Depot;
use App\Entity\Expediteur;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montant')
            ->add('expediteur',EntityType::class,[
                'class'         =>Expediteur::class,
                'choice_label'  =>'nom']
            )
            ->add('beneficiaire',EntityType::class,[
                'class'         =>Beneficiaire::class,
                'choice_label'  =>'nom']
                )
            
            ->add('ville',EntityType::class,[
                'class'         =>City::class,
                'choice_label'  =>'name']
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Depot::class,
        ]);
    }
}
