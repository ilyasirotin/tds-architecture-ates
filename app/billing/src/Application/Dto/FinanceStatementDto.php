<?php

declare(strict_types=1);

namespace App\Application\Dto;

use App\Entity\Account;
use App\Entity\Transaction;

final class FinanceStatementDto
{
    /** @var array<Transaction> */
    private array $transactions;
    private Account $account;

    /**
     * @param array<Transaction> $transactions
     */
    public function __construct(array $transactions, Account $account)
    {
        $this->transactions = $transactions;
        $this->account = $account;
    }

    public function transactions(): array
    {
        return $this->transactions;
    }

    public function account(): Account
    {
        return $this->account;
    }
}
