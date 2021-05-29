<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\User;
use App\Entity\Depot;
use App\Form\CityType;
use App\Entity\Expediteur;
use App\Entity\Beneficiaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('montant')
            ->add(
                'expediteur',
                ExpediteurType::class
            )
            ->add(
                'beneficiaire',
                BeneficiaireType::class
            )

            ->add(
                'ville',
                CityType::class
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Depot::class,
        ]);
    }
}
