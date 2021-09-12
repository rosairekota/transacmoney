<?php

namespace App\Form;

use App\Entity\Compte;
use App\Entity\Credit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CreditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('credit_amount', IntegerType::class, ['label' => 'Inserer le Montant'])
            ->add('account', EntityType::class, [
                'label' => "Selectioner le numéro de Compte de l'agence",
                'class' => Compte::class,
                'choice_label' => 'numero_compte'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Credit::class,
        ]);
    }
}
