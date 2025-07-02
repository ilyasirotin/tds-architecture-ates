<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\Task;

interface ViewTasksUseCase
{
    /**
     * @return array<Task>
     */
    public function execute(ViewTasks $query): array;
}
