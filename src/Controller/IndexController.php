<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\RecipeRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class IndexController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(RecipeRepository $recipeRepository): Response
    {
        $user = $this->getUser();
        $recipes = $recipeRepository->findBy([], ['createdAt' => 'DESC'], 9);

        return $this->render('index/index.html.twig', [
            'recipes' => $recipes,
            'user' => $user,
        ]);
    }

    #[Route('/recipe/{id}', name: 'recipe_show')]
    public function showRecipe(
        int $id,
        RecipeRepository $recipeRepository,
        CommentRepository $commentRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $recipe = $recipeRepository->find($id);

        if (!$recipe) {
            return $this->redirectToRoute('index');
        }

        $comment = new Comment();
        $comment->setRecipe($recipe);

        $now = new \DateTimeImmutable();
        $comment->setCreatedAt($now);

        $user = $this->getUser();
        if ($user) {
            $comment->setUser($user);
        }

        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $user) {
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('recipe_show', ['id' => $id]);
        }

        $comments = $commentRepository->findBy(['recipe' => $recipe], ['created_at' => 'DESC']);

        return $this->render('index/show.html.twig', [
            'recipe' => $recipe,
            'user' => $user,
            'commentForm' => $form->createView(),
            'comments' => $comments,
        ]);
    }

    #[Route('/recipe/delete/comment/{id}', name: 'recipe_delete_comment')]
    public function deleteComment(Comment $comment, EntityManagerInterface $em): RedirectResponse
    {
        $user = $this->getUser();

        $recipeId = $comment->getRecipe()->getId();

        $em->remove($comment);
        $em->flush();

        return $this->redirectToRoute('recipe_show', ['id' => $recipeId]);
    }
}
