<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function addRecipe(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recipe = new Recipe();
        $recipe->setAuthor($this->getUser());

        $form = $this->createForm(RecipeFormType::class, $recipe);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('admin_recipe_index');
        }

        return $this->render('admin/admin_recipe/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
