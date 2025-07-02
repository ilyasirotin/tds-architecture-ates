<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\User;

final class ViewFinanceStatement
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function user(): User
    {
        return $this->user;
    }
}
