<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/category')]
final class AdminCategoryController extends AbstractController
{
    #[Route('', name: 'admin_category_index')]
    public function index(): Response
    {
        return $this->render('admin/admin_category/index.html.twig');
    }
}
