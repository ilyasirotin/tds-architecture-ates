<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\Task;

interface CompleteTaskUseCase
{
    public function execute(CompleteTask $command): Task;
}
