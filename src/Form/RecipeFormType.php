<?php

namespace App\Form;

use App\Entity\Recipe;
use App\Entity\Category;
use App\Entity\Difficulty;
use App\Form\QuantityFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RecipeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Tarte aux pommes',
                ],
            ])
            ->add('image', TextType::class, [
                'required' => false,
                'label' => 'Image',
                'attr' => [
                    'placeholder' => 'URL de l\'image',
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Délicieuse tarte classique à la pâte sablée, garnie de fines tranches de pommes caramélisées à la cannelle. Parfaite avec une boule de glace vanille !',
                    'rows' => 5,
                ],
            ])
            ->add('serving', NumberType::class, [
                'required' => true,
                'label' => 'Portion(s)',
                'attr' => [
                    'min' => 1,
                    'max' => 50,
                    'placeholder' => '6',
                ],
            ])
            ->add('preparationTime', NumberType::class, [
                'required' => true,
                'label' => 'Temps de préparation (en minutes)',
                'attr' => [
                    'min' => 1,
                    'max' => 600,
                    'placeholder' => '60',
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'label',
                'label' => false,
                'placeholder' => 'Catégorie',
            ])
            ->add('difficulty', EntityType::class, [
                'class' => Difficulty::class,
                'choice_label' => 'label',
                'label' => false,
                'placeholder' => 'Difficulté',
            ])
            ->add('quantity', CollectionType::class, [
                'entry_type' => QuantityFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'label' => false,
                'attr' => ['class' => 'quantity-collection'],
            ])
            ->add('step', CollectionType::class, [
                'entry_type' => StepFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
