<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\User;

interface HandleUserDataUseCase
{
    public function execute(HandleUserData $command): User;
}
