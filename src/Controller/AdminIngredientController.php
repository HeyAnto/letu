<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/ingredient')]
final class AdminIngredientController extends AbstractController
{
    #[Route('', name: 'admin_ingredient_index')]
    public function index(): Response
    {
        return $this->render('admin/admin_ingredient/index.html.twig');
    }

    #[Route('/{id}', name: 'admin_ingredient_show')]
    public function show(): Response
    {
        return $this->render('admin/admin_ingredient/show.html.twig');
    }

    #[Route('/add/recipe', name: 'admin_ingredient_add')]
    public function addRecipe(): Response
    {
        return $this->render('admin/admin_ingredient/add.html.twig');
    }
}
