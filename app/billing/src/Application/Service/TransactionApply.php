<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\Account;

final class TransactionApply
{
    private Account $account;
    private string $type;
    private string $amount;

    public function __construct(Account $account, string $type, string $amount)
    {
        $this->account = $account;
        $this->type = $type;
        $this->amount = $amount;
    }

    public function account(): Account
    {
        return $this->account;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function amount(): string
    {
        return $this->amount;
    }
}
