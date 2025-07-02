<?php

declare(strict_types=1);

namespace App\Application\Services;

interface RegisterUserUseCase
{
    public function execute(RegisterUser $command);
}
