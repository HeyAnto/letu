<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeFormType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/recipe')]
final class AdminRecipeController extends AbstractController
{
    #[Route('', name: 'admin_recipe_index')]
    public function index(RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findAll();

        return $this->render('admin/admin_recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/{id}', name: 'admin_recipe_show')]
    public function show(): Response
    {
        return $this->render('admin/admin_recipe/show.html.twig');
    }

    #[Route('/add/recipe', name: 'admin_recipe_add')]
    public function addRecipe(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recipe = new Recipe();
        $recipe->setAuthor($this->getUser());

        $form = $this->createForm(RecipeFormType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$recipe->getImage()) {
                $recipe->setImage('/images/default-recipe.jpg');
            }

            $now = new \DateTimeImmutable();
            $recipe->setCreatedAt($now);
            $recipe->setUpdatedAt($now);

            foreach ($recipe->getQuantity() as $quantity) {
                $quantity->setRecipe($recipe);
                $entityManager->persist($quantity);
            }

            foreach ($recipe->getStep() as $step) {
                $step->setRecipe($recipe);
                $entityManager->persist($step);
            }

            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('admin_recipe_index');
        }

        return $this->render('admin/admin_recipe/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
