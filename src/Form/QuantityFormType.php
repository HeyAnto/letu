<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Quantity;
use App\Entity\Recipe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuantityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount')
            ->add('unit')
            ->add('recipe', EntityType::class, [
                'class' => Recipe::class,
                'choice_label' => 'id',
            ])
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'id',
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
