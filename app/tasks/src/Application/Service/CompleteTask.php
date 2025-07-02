<?php

declare(strict_types=1);

namespace App\Application\Service;

final class CompleteTask
{
    private int $taskId;

    public function __construct(int $taskId)
    {
        $this->taskId = $taskId;
    }

    public function taskId(): int
    {
        return $this->taskId;
    }
}
