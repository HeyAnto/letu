<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeFormType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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

    #[Route('/add', name: 'admin_recipe_add')]
    public function add(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/images/recipes')] string $imageDirectory
    ): Response {
        $recipe = new Recipe();
        $recipe->setAuthor($this->getUser());

        $form = $this->createForm(RecipeFormType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();

            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move($imageDirectory, $newFilename);
                } catch (FileException $e) {
                    return new Response("Erreur lors de l'upload de l'image");
                }

                $recipe->setImage('images/recipes/' . $newFilename);
            } else {
                $recipe->setImage('images/recipes/img-recipe-default.webp');
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

    #[Route('/{id}', name: 'admin_recipe_show')]
    public function show(RecipeRepository $recipeRepository, int $id): Response
    {
        $recipe = $recipeRepository->find($id);

        return $this->render('admin/admin_recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/edit/{id}', name: 'admin_recipe_edit')]
    public function edit(
        int $id,
        RecipeRepository $recipeRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/images/recipes')] string $imageDirectory
    ): Response {
        $recipe = $recipeRepository->find($id);

        if (!$recipe) {
            return new Response("Recette non trouvée", 404);
        }

        $form = $this->createForm(RecipeFormType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();

            if ($image) {
                $oldImage = $recipe->getImage();
                if ($oldImage && $oldImage !== 'images/recipes/img-recipe-default.webp') {
                    $oldFilePath = $imageDirectory . '/' . basename($oldImage);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

                try {
                    $image->move($imageDirectory, $newFilename);
                } catch (FileException $e) {
                    return new Response("Erreur lors de l'upload de l'image");
                }

                $recipe->setImage('images/recipes/' . $newFilename);
            }

            $now = new \DateTimeImmutable();
            $recipe->setUpdatedAt($now);

            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('admin_recipe_index');
        }

        return $this->render('admin/admin_recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_recipe_delete')]
    public function delete(RecipeRepository $recipeRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $recipe = $recipeRepository->find($id);
        $entityManager->remove($recipe);
        $entityManager->flush();

        return $this->redirectToRoute('admin_recipe_index');
    }
}
