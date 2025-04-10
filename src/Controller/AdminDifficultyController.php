<?php

namespace App\Controller;

use App\Repository\DifficultyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/difficulty')]
final class AdminDifficultyController extends AbstractController
{
    #[Route('', name: 'admin_difficulty_index')]
    public function index(DifficultyRepository $difficultyRepository): Response
    {
        $difficulties = $difficultyRepository->findAll();

        return $this->render('admin/admin_difficulty/index.html.twig', [
            'difficulties' => $difficulties,
        ]);
    }

    #[Route('/add/difficulty', name: 'admin_difficulty_add')]
    public function addRecipe(): Response
    {
        return $this->render('admin/admin_difficulty/add.html.twig');
    }

    #[Route('/{id}', name: 'admin_difficulty_show')]
    public function show(): Response
    {
        return $this->render('admin/admin_difficulty/show.html.twig');
    }

    #[Route('/delete/difficulty/{id}', name: 'admin_difficulty_delete')]
    public function delete(DifficultyRepository $difficultyRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $difficulty = $difficultyRepository->find($id);
        $entityManager->remove($difficulty);
        $entityManager->flush();

        return $this->redirectToRoute('admin_difficulty_index');
    }
}
