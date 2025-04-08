<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/difficulty')]
final class AdminDifficultyController extends AbstractController
{
    #[Route('', name: 'admin_difficulty_index')]
    public function index(): Response
    {
        return $this->render('admin/admin_difficulty/index.html.twig');
    }

    #[Route('/{id}', name: 'admin_difficulty_show')]
    public function show(): Response
    {
        return $this->render('admin/admin_difficulty/show.html.twig');
    }

    #[Route('/add/recipe', name: 'admin_difficulty_add')]
    public function addRecipe(): Response
    {
        return $this->render('admin/admin_difficulty/add.html.twig');
    }
}
