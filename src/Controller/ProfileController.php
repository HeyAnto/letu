<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Recipe;
use App\Form\RecipeFormType;
use App\Form\ProfileFormType;
use App\Repository\UserRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/profile')]
final class ProfileController extends AbstractController
{
    #[Route('', name: 'profile_index')]
    public function profileIndex(RecipeRepository $recipeRepository): Response
    {
        $user = $this->getUser();
        $recipes = $recipeRepository->findBy(['author' => $user]);

        return $this->render('profile/index.html.twig', [
            'recipes' => $recipes,
            'user' => $user,
        ]);
    }

    #[Route('/{id}', name: 'profile_author')]
    public function author(int $id, UserRepository $userRepository, RecipeRepository $recipeRepository): Response
    {
        /** @var User|null $currentUser */
        $currentUser = $this->getUser();

        $user = $userRepository->find($id);
        if (!$user) {
            return $this->redirectToRoute('profile_index');
        }

        if ($currentUser instanceof User && $currentUser->getId() === $user->getId()) {
            return $this->redirectToRoute('profile_index');
        }

        $recipes = $recipeRepository->findBy(['author' => $user]);

        return $this->render('profile/index.html.twig', [
            'recipes' => $recipes,
            'user' => $user,
        ]);
    }

    #[Route('/edit/{id}', name: 'profile_edit')]
    public function profile(
        int $id,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/images/profile-pictures')] string $profilePictureDirectory
    ): Response {
        $user = $userRepository->find($id);

        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $profilePicture */
            $profilePicture = $form->get('profilePicture')->getData();

            if ($profilePicture) {
                $oldProfilePicture = $user->getprofilePicture();
                if ($oldProfilePicture && $oldProfilePicture !== 'images/profile-pictures/pp-default.svg') {
                    $oldFilePath = $profilePictureDirectory . '/' . basename($oldProfilePicture);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $originalFilename = pathinfo($profilePicture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $profilePicture->guessExtension();

                try {
                    $profilePicture->move($profilePictureDirectory, $newFilename);
                } catch (FileException $e) {
                    return new Response("Erreur lors de l'upload de l'image de profil");
                }

                $user->setprofilePicture('images/profile-pictures/' . $newFilename);
            }

            $now = new \DateTimeImmutable();
            $user->setUpdatedAt($now);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('profile_index');
        }

        return $this->render('profile/profile-edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/recipe/add', name: 'profile_recipe_add')]
    public function add(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/images/recipes')] string $imageDirectory
    ): Response {
        $recipe = new Recipe();
        $recipe->setAuthor($this->getUser());

        $form = $this->createForm(RecipeFormType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move($imageDirectory, $newFilename);
                } catch (FileException $e) {
                    return new Response("Erreur lors de l'upload de l'image");
                }

                $recipe->setImage('images/recipes/' . $newFilename);
            } else {
                $recipe->setImage('images/recipes/img-recipe-default.webp');
            }

            $now = new \DateTimeImmutable();
            $recipe->setCreatedAt($now);
            $recipe->setUpdatedAt($now);

            foreach ($recipe->getQuantity() as $quantity) {
                $quantity->setRecipe($recipe);
                $entityManager->persist($quantity);
            }

            foreach ($recipe->getStep() as $step) {
                $step->setRecipe($recipe);
                $entityManager->persist($step);
            }

            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('profile_index');
        }

        return $this->render('profile/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('recipe/edit/{id}', name: 'profile_recipe_edit')]
    public function edit(
        int $id,
        Request $request,
        RecipeRepository $recipeRepository,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/images/recipes')] string $imageDirectory
    ): Response {
        $recipe = $recipeRepository->find($id);

        if (!$recipe) {
            $this->addFlash('error', 'Recette non trouvée');
            return $this->redirectToRoute('profile_index');
        }

        $oldImage = $recipe->getImage();

        $form = $this->createForm(RecipeFormType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();

            if ($image) {
                if ($oldImage && $oldImage !== 'images/recipes/img-recipe-default.webp') {
                    $oldImagePath = $this->getParameter('kernel.project_dir') . '/public/' . $oldImage;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move($imageDirectory, $newFilename);
                    $recipe->setImage('images/recipes/' . $newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image');
                    return $this->redirectToRoute('profile_recipe_edit', ['id' => $id]);
                }
            }

            $now = new \DateTimeImmutable();
            $recipe->setUpdatedAt($now);

            foreach ($recipe->getQuantity() as $quantity) {
                if (!$quantity->getRecipe()) {
                    $quantity->setRecipe($recipe);
                }
                $entityManager->persist($quantity);
            }

            foreach ($recipe->getStep() as $step) {
                if (!$step->getRecipe()) {
                    $step->setRecipe($recipe);
                }
                $entityManager->persist($step);
            }

            $recipe->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->flush();
            $this->addFlash('success', 'Recette mise à jour avec succès!');

            return $this->redirectToRoute('profile_index');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
            'recipe' => $recipe,
        ]);
    }

    #[Route('/recipe/delete/{id}', name: 'profile_recipe_delete')]
    public function delete(
        RecipeRepository $recipeRepository,
        EntityManagerInterface $entityManager,
        int $id
    ): Response {
        $recipe = $recipeRepository->find($id);

        if (!$recipe) {
            return $this->redirectToRoute('index');
        }

        $user = $this->getUser();
        if (!$user || $recipe->getAuthor() !== $user) {
            return $this->redirectToRoute('profile_index');
        }

        $entityManager->remove($recipe);
        $entityManager->flush();

        return $this->redirectToRoute('profile_index');
    }
}
