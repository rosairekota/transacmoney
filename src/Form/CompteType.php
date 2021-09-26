<?php

namespace App\Form;


use App\Entity\Compte;
use App\Form\UserFormType as UserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CompteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)

    {
        $builder

            ->add('solde', IntegerType::class, ['label' => false]);
        // ->add('user', UserType::class, ['label' => 'Respnsable ']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Compte::class,
        ]);
    }
}
