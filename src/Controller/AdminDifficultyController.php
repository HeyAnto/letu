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
}
