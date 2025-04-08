<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/user')]
final class AdminUserController extends AbstractController
{
    #[Route('', name: 'admin_user_index')]
    public function index(): Response
    {
        return $this->render('admin/admin_user/index.html.twig');
    }

    #[Route('/{id}', name: 'admin_user_show')]
    public function show(): Response
    {
        return $this->render('admin/admin_user/show.html.twig');
    }
}
