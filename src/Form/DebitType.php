<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Debit;
use App\Entity\Agence;
use App\Entity\Compte;
use App\Form\AgenceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class DebitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount', IntegerType::class, ['label' => 'Montant'])
            ->add('account_number', null, [
                'label' => "Inserer le numéro de votre Compte",
                'mapped' => true,
                'required' => false
            ])
            ->add('user', EntityType::class, [
                'label' => "Selectioner l'agent beneficiaire",
                'class' => User::class,
                'choice_label' => 'nomComplet'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Debit::class,
        ]);
    }
}
