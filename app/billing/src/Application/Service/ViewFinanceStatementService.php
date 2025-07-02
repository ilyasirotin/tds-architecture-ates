<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Dto\FinanceStatementDto;
use App\Repository\TransactionRepository;

final class ViewFinanceStatementService implements ViewFinanceStatementUseCase
{
    private TransactionRepository $transactions;

    public function __construct(TransactionRepository $transactions)
    {
        $this->transactions = $transactions;
    }

    public function execute(ViewFinanceStatement $query): FinanceStatementDto
    {
        $account = $query->user()->getAccount();
        $transactions = $this->transactions->getDailyTransactionsLog($account);

        return (new FinanceStatementDto($transactions, $account));
    }
}
