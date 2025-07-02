<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\Task;

final class HandleTaskData
{
    private Task $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function task(): Task
    {
        return $this->task;
    }
}
