<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class IndexController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(RecipeRepository $recipeRepository): Response
    {
        $user = $this->getUser();
        $recipes = $recipeRepository->findBy([], ['createdAt' => 'DESC'], 9);

        return $this->render('index/index.html.twig', [
            'recipes' => $recipes,
            'user' => $user,
        ]);
    }

    #[Route('/recipe/{id}', name: 'recipe_show')]
    public function showRecipe(int $id, RecipeRepository $recipeRepository): Response
    {
        $recipe = $recipeRepository->find($id);

        if (!$recipe) {
            return $this->redirectToRoute('index');
        }

        return $this->render('index/show.html.twig', [
            'recipe' => $recipe,
            'user' => $this->getUser(),
        ]);
    }
}
