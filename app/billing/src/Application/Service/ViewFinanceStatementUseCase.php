<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Dto\FinanceStatementDto;

interface ViewFinanceStatementUseCase
{
    public function execute(ViewFinanceStatement $query): FinanceStatementDto;
}
