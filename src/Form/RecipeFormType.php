<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Difficulty;
use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RecipeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('image')
            ->add('description')
            ->add('serving')
            ->add('preparationTime')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'label',
            ])
            ->add('difficulty', EntityType::class, [
                'class' => Difficulty::class,
                'choice_label' => 'label',
            ])
            ->add('quantities', CollectionType::class, [
                'entry_type' => QuantityFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Ingredients',
            ])
            ->add('steps', CollectionType::class, [
                'entry_type' => StepFormType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Steps',
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
