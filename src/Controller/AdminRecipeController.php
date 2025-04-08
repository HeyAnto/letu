<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/recipe')]
final class AdminRecipeController extends AbstractController
{
    #[Route('', name: 'admin_recipe_index')]
    public function index(): Response
    {
        return $this->render('admin/admin_recipe/index.html.twig', [
            'controller_name' => 'AdminRecipeController',
        ]);
    }

    #[Route('/{id}', name: 'admin_recipe_show')]
    public function show(): Response
    {
        return $this->render('admin/admin_recipe/show.html.twig', [
            'controller_name' => 'AdminRecipeController',
        ]);
    }

    #[Route('/add/recipe', name: 'admin_recipe_add')]
    public function addRecipe(): Response
    {
        return $this->render('admin/admin_recipe/add.html.twig', [
            'controller_name' => 'AdminRecipeController',
        ]);
    }
}
