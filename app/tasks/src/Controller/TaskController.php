<?php

namespace App\Controller;

use App\Application\Service\AddNewTask;
use App\Application\Service\AddNewTaskUseCase;
use App\Application\Service\CompleteTask;
use App\Application\Service\CompleteTaskUseCase;
use App\Application\Service\ShuffleTasks;
use App\Application\Service\ShuffleTasksUseCase;
use App\Entity\Task;
use App\Form\AddTaskForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TaskController extends AbstractController
{
    #[Route('/add', name: 'app_task_add')]
    public function add(AddNewTaskUseCase $service, Request $request): Response
    {
        $task = new Task();

        $form = $this->createForm(AddTaskForm::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->execute(
                new AddNewTask($task)
            );

            return $this->redirectToRoute('app_index');
        }

        return $this->render('task/add.html.twig', [
            'addTaskForm' => $form,
        ]);
    }

    #[Route('/task/complete', name: 'app_task_complete')]
    public function complete(CompleteTaskUseCase $service, Request $request): Response
    {
        $taskId = (int) $request->get('id');

        $service->execute(
            new CompleteTask($taskId)
        );

        return $this->redirectToRoute('app_index');
    }

    #[IsGranted('ROLE_ADMIN')]
    #[IsGranted('ROLE_MANAGER')]
    #[Route('/task/shuffle', name: 'app_task_shuffle')]
    public function shuffle(ShuffleTasksUseCase $service, Request $request): Response
    {
        // TODO: To be implemented

        /**
         * Need to put this command to some kind of background
         * batch processing scheduler to take off the load.
         */
        $service->execute(
            new ShuffleTasks()
        );

        return $this->redirectToRoute('app_index');
    }
}
