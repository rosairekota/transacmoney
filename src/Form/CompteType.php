<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Compte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('montant_debit', null, ['label' => 'Inserer le montant caisse'])
            ->add('user_compte', EntityType::class, [
                'choice_label' => "username",
                'label' => "Choisir l'agent",
                "class" => User::class
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Compte::class,
        ]);
    }
}
