<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Listing;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/task')]
class TaskController extends AbstractController
{
    #[Route('/new/{id}', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Listing $listing, ManagerRegistry $doctrine): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $task->setTitle($task->getTitle());
            $task->setState($task->isState());
            $task->setListing($listing);
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', $task->getTitle() . ' à bien été crée avec succés !');
            return $this->redirectToRoute('app_listing_show', ['id' => $task->getListing()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/new.html.twig', [
            'task' => $task,
            'listing' => $listing,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/state_changement/{id}', name: 'app_task_switch', methods: ['POST'])]
    public function switchState(Request $request, Task $task, ManagerRegistry $doctrine): Response
    {

        if ($this->isCsrfTokenValid('switch' . $task->getId(), $request->request->get('_token'))) {
            $state = $task->isState() ? false : true;
            $entityManager = $doctrine->getManager();
            $task->setState($state);
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'La modification de l\'état de ' . $task->getTitle() . ' à correctement étè éffectué  avec succés !');
        }

        return $this->redirectToRoute('app_listing_show', ['id' => $task->getListing()->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $task->setTitle($task->getTitle());
            $task->isState($task->isState());
            $entityManager->persist($task);
            $entityManager->flush();
            $this->addFlash('success', 'Task éditer avec succés !');
            return $this->redirectToRoute('app_listing_show', ['id' => $task->getListing()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, TaskRepository $taskRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            $taskRepository->remove($task, true);
            $this->addFlash('success', $task->getTitle() . ' à bien été supprimé avec succés !');
        }

        return $this->redirectToRoute('app_listing_show', ['id' => $task->getListing()->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/allTask/{id}', name: 'app_task_delete_all', methods: ['POST'])]
    public function deleteAlltask(Request $request, ManagerRegistry $doctrine, Listing $listing, taskRepository $taskRepository): Response
    {

        if ($this->isCsrfTokenValid('delete' . $listing->getId(), $request->request->get('_token'))) {
             $doctrine->getRepository(Task::class)->removeAllTasks($listing->getId(), true);
             $this->addFlash('success', 'Les tasks déjà éfféctués ont bien été supprimé avec succés !');
        }

        return $this->redirectToRoute('app_listing_show', ['id' => $listing->getId()], Response::HTTP_SEE_OTHER);
    }
}
