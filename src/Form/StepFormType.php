<?php

namespace App\Form;

use App\Entity\Step;
use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class StepFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stepNumber', NumberType::class, [
                'label' => 'Étape n°',
                'required' => true,
                'attr' => [
                    'min' => 1,
                    'max' => 20,
                    'placeholder' => 'Numéro de l\'étape',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Description de l\'étape',
                    'rows' => 5,
                ],
            ])
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'label',
                'multiple' => true,
                'expanded' => false,
                'label' => 'Ingrédients',
                'attr' => [
                    'class' => 'custom-ingredient-select',
                    'size' => 5,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Step::class,
        ]);
    }
}
