<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/ingredient')]
final class AdminIngredientController extends AbstractController
{
    #[Route('', name: 'admin_ingredient_index')]
    public function index(IngredientRepository $ingredientRepository): Response
    {
        $ingredients = $ingredientRepository->findAll();

        return $this->render('admin/admin_ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/add/ingredient', name: 'admin_ingredient_add')]
    public function addRecipe(): Response
    {
        return $this->render('admin/admin_ingredient/add.html.twig');
    }

    #[Route('/{id}', name: 'admin_ingredient_show')]
    public function show(): Response
    {
        return $this->render('admin/admin_ingredient/show.html.twig');
    }

    #[Route('/delete/ingredient/{id}', name: 'admin_ingredient_delete')]
    public function delete(IngredientRepository $ingredientRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $ingredient = $ingredientRepository->find($id);
        $entityManager->remove($ingredient);
        $entityManager->flush();

        return $this->render('admin/admin_ingredient/index.html.twig');
    }
}
