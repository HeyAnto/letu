<?php

namespace App\Controller;

use App\Entity\Difficulty;
use App\Form\DifficultyFormType;
use App\Repository\DifficultyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;
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

    #[Route('/add', name: 'admin_difficulty_add')]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $difficulty = new Difficulty();

        $form = $this->createForm(DifficultyFormType::class, $difficulty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($difficulty);
            $entityManager->flush();
            return $this->redirectToRoute('admin_difficulty_index');
        }

        return $this->render('admin/admin_difficulty/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'admin_difficulty_show')]
    public function show(DifficultyRepository $difficultyRepository, int $id): Response
    {
        $difficulty = $difficultyRepository->find($id);

        return $this->render('admin/admin_difficulty/show.html.twig', [
            'difficulty' => $difficulty,
        ]);
    }

    #[Route('/edit/{id}', name: 'admin_difficulty_edit')]
    public function edit(int $id, DifficultyRepository $difficultyRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        $difficulty = $difficultyRepository->find($id);

        if (!$difficulty) {
            return new Response("Difficulté non trouvée", 404);
        }

        $form = $this->createForm(difficultyFormType::class, $difficulty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($difficulty);
            $entityManager->flush();
            return $this->redirectToRoute('admin_difficulty_index');
        }

        return $this->render('admin/admin_difficulty/edit.html.twig', [
            'difficulty' => $difficulty,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'admin_difficulty_delete')]
    public function delete(DifficultyRepository $difficultyRepository, EntityManagerInterface $entityManager, int $id): Response
    {
        $difficulty = $difficultyRepository->find($id);
        $entityManager->remove($difficulty);
        $entityManager->flush();

        return $this->redirectToRoute('admin_difficulty_index');
    }
}
