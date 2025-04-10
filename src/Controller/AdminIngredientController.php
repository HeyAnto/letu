<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientFormType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
    public function add(
        EntityManagerInterface $entityManager,
        Request $request,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/images/ingredients')] string $imageDirectory
    ): Response {

        $ingredient = new Ingredient();

        if (!$ingredient) {
            return new Response("Produit non trouvé", 404);
        }

        $form = $this->createForm(IngredientFormType::class, $ingredient);
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

                $ingredient->setImage('images/ingredients/' . $newFilename);
            } else {
                $ingredient->setImage('images/ingredients/img-ingredient-default.webp');
            }

            $entityManager->persist($ingredient);
            $entityManager->flush();

            return $this->redirectToRoute('admin_ingredient_index');
        }

        return $this->render('admin/admin_ingredient/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_ingredient_show')]
    public function show(): Response
    {
        return $this->render('admin/admin_ingredient/show.html.twig');
    }

    #[Route('/edit/ingredient/{id}', name: 'admin_ingredient_edit')]
    public function edit(
        int $id,
        IngredientRepository $ingredientRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/images/ingredients')] string $imageDirectory
    ): Response {
        $ingredient = $ingredientRepository->find($id);

        if (!$ingredient) {
            return new Response("Produit non trouvé", 404);
        }

        $form = $this->createForm(IngredientFormType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData();

            if ($image) {
                $oldImage = $ingredient->getImage();
                if ($oldImage && $oldImage !== 'images/default.webp') {
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

                $ingredient->setImage('images/ingredients/' . $newFilename);
            }

            $entityManager->persist($ingredient);
            $entityManager->flush();

            return $this->redirectToRoute('admin_ingredient_index');
        }

        return $this->render('admin/admin_ingredient/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/ingredient/{id}', name: 'admin_ingredient_delete')]
    public function delete(IngredientRepository $ingredientRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $ingredient = $ingredientRepository->find($id);
        $entityManager->remove($ingredient);
        $entityManager->flush();

        return $this->redirectToRoute('admin_ingredient_index');
    }
}
