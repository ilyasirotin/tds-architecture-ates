<?php

declare(strict_types=1);

namespace App\Console;

use App\Entity\Payment;
use App\Repository\PaymentRepository;
use Enqueue\Client\Message;
use Enqueue\Client\ProducerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'app:process-payments',
    description: 'Process pending payments',
    aliases: ['app:process-payments'],
    hidden: false
)]
final class ProcessPaymentsCommand extends Command
{
    private PaymentRepository $payments;
    private ProducerInterface $producer;
    private SerializerInterface $serializer;

    public function __construct(
        PaymentRepository $payments,
        ProducerInterface $producer,
        SerializerInterface $serializer,
    )
    {
        parent::__construct();

        $this->payments = $payments;
        $this->producer = $producer;
        $this->serializer = $serializer;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // TODO: Implement payments processing

        // Stub payment
        $payment = new Payment();

        $message = new Message(
            $this->serializer->serialize($payment, 'json')
        );

        $this->producer->sendEvent('payment_completed', $message);

        return self::SUCCESS;
    }
}
