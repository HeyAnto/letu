<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    #[Route('/edit', name: 'profile_edit')]
    public function profile(): Response
    {
        return $this->render('profile/index.html.twig');
    }

    #[Route('/recipe/add', name: 'profile_recipe_add')]
    public function add(): Response
    {
        return $this->render('profile/index.html.twig');
    }

    #[Route('/recipe/edit', name: 'profile_recipe_edit')]
    public function edit(): Response
    {
        return $this->render('profile/index.html.twig');
    }

    #[Route('/recipe/delete', name: 'profile_recipe_delete')]
    public function delete(): Response
    {
        return $this->render('profile/index.html.twig');
    }
}
