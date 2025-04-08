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
}
