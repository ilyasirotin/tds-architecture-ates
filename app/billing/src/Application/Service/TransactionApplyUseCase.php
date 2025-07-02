<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\Transaction;

interface TransactionApplyUseCase
{
    public function execute(TransactionApply $command): Transaction;
}
