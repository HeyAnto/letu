<?php

namespace App\Form;

use App\Entity\Quantity;
use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class QuantityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'label',
                'label' => 'Ingrédient',
                'required' => true,
                'placeholder' => 'Ingrédient',
            ])
            ->add('amount', NumberType::class, [
                'label' => 'Quantité',
                'required' => false,
                'attr' => [
                    'min' => 0.1,
                    'max' => 2000,
                    'step' => 0.1,
                    'placeholder' => 'Quantité',
                ],
            ])
            ->add('unit', TextType::class, [
                'label' => 'Unité (ex: g, ml, cuillère, cup...)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Unité',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quantity::class,
        ]);
    }
}
