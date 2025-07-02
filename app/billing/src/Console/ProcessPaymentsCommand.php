<?php

declare(strict_types=1);

namespace App\Console;

use App\Repository\PaymentRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:process-payments',
    description: 'Process pending payments',
    aliases: ['app:process-payments'],
    hidden: false
)]
final class ProcessPaymentsCommand extends Command
{
    private PaymentRepository $payments;

    public function __construct(PaymentRepository $payments)
    {
        parent::__construct();

        $this->payments = $payments;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // TODO: Implement payments processing

        return self::SUCCESS;
    }
}
