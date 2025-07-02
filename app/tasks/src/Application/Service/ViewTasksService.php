<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;

final class ViewTasksService implements ViewTasksUseCase
{
    private TaskRepository $tasks;

    public function __construct(TaskRepository $tasks)
    {
        $this->tasks = $tasks;
    }

    /**
     * @return array<Task>
     */
    public function execute(ViewTasks $query): array
    {
        $user = $query->user();

        // TODO: Refactor roles checking
        if (count(array_intersect(['ROLE_ADMIN', 'ROLE_MANAGER'], $user->getRoles())) > 0) {
            return $this->tasks->findAll();
        }

        return $this->tasks->findAssignedTasks($user);
    }
}
