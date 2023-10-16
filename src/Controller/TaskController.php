<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/task')]
class TaskController extends AbstractController
{
    public function __construct(private TaskRepository $taskRepository) {
    }

    #[Route('/', name: 'app_admin_task')]
    public function index(): Response
    {
        $tasks = $this->taskRepository->findAll();

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks
        ]);
    }

    #[Route('/new', name: 'app_admin_task_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        //$task = new Task();

        $form = $this->createForm(TaskType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $task = $form->getData();
            
            // get value field no mapped, above not found it in $task, because no mapped
            //$agreeTerms = $form->get('agreeTerms')->getData();


            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'You task has benn saved.');

            return $this->redirectToRoute('app_admin_task');
        }

        return $this->renderForm('task/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_admin_task_edit')]
    public function edit(Task $task, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $task = $form->getData();

            // get value field no mapped, above not found it in $task, because no mapped
            //$agreeTerms = $form->get('agreeTerms')->getData();

            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'You task has benn updated.');

            return $this->redirectToRoute('app_admin_task');
        }

        return $this->renderForm('task/edit.html.twig', [
            'form' => $form,
        ]);
    }
}
