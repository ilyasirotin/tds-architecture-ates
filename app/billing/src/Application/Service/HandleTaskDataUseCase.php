<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\Task;

interface HandleTaskDataUseCase
{
    public function execute(HandleTaskData $command): Task;
}
