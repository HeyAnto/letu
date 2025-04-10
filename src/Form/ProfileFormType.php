<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints as Assert;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom d\'utilisateur',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrer un nom d\'utilisateur',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Votre nom d\'utilisateur doit contenir au moins {{ limit }} caractères',
                        'max' => 20,
                        'maxMessage' => 'Votre nom d\'utilisateur ne doit pas dépasser {{ limit }} caractères',
                    ]),
                ],
                'attr' => [
                    'autocomplete' => false,
                    'placeholder' => 'Nom d\'utilisateur',
                ],
            ])
            ->add('profilePicture', FileType::class, [
                'label' => "Image de profil",
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG, WEBP)',
                    ])
                ],
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le prénom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'min' => 2,
                        'max' => 50,
                        'minMessage' => 'Le nom doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ]
            ])
            ->add('birthAt', DateType::class, [
                'label' => 'Date de naissance',
                'widget' => 'single_text',
                'required' => false,
                'constraints' => [
                    new Assert\LessThan([
                        'value' => 'today',
                        'message' => 'La date de naissance doit être dans le passé.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
