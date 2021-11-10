<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prix', IntegerType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control text-center',
                    'value' => '490',
                    'min' => '1',
                    'data-bind' => 'value:replyNumber'
                ]
            ])
            ->add('quantite', IntegerType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'form-control input-number text-center',
                    'value' => '1',
                    'min' => '1'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
