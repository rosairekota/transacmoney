<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Agence;
use App\Form\CompteType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => `Nom de l'agence`])
            ->add('description', null, ['label' => 'Adresse'])
            ->add('city', EntityType::class, [
                'class'         => City::class,
                'choice_label'  => 'name',
                'label' => 'Ville'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Agence::class,
        ]);
    }
}
