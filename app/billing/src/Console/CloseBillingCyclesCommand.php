<?php

declare(strict_types=1);

namespace App\Console;

use App\Application\Service\TransactionApply;
use App\Application\Service\TransactionApplyUseCase;
use App\Entity\Transaction;
use App\Repository\BillingCycleRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:close-billing-cycles',
    description: 'Close billing cycles for the past 24 hours',
    aliases: ['app:close-billing-cycles'],
    hidden: false
)]
final class CloseBillingCyclesCommand extends Command
{
    private BillingCycleRepository $billingCycles;
    private TransactionApplyUseCase $service;

    public function __construct(
        BillingCycleRepository $billingCycles,
        TransactionApplyUseCase $service
    )
    {
        parent::__construct();

        $this->billingCycles = $billingCycles;
        $this->service = $service;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $billingCyclesToClose = $this->billingCycles->findToClose();

        foreach ($billingCyclesToClose as $billingCycle) {
            $account = $billingCycle->getAccount();

            $this->service->execute(
                new TransactionApply(
                    $account,
                    Transaction::DISBURSEMENT,
                    $account->getBalance(),
                )
            );
        }

        return self::SUCCESS;
    }
}
