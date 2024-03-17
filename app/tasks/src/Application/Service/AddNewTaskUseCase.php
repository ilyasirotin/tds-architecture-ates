<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\Task;

interface AddNewTaskUseCase
{
    public function execute(AddNewTask $command): Task;
}
