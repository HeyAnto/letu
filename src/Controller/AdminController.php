<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class AdminController extends AbstractController
{
    #[Route('', name: 'admin_index')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }
}
