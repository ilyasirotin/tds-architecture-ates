<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Repository\UserRepository;
use Enqueue\Client\Message;
use Enqueue\Client\ProducerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class RegisterUserService implements RegisterUserUseCase
{
    private UserRepository $users;
    private ProducerInterface $producer;
    private SerializerInterface $serializer;

    public function __construct(
        UserRepository $users,
        ProducerInterface $producer,
        SerializerInterface $serializer
    )
    {
        $this->users = $users;
        $this->producer = $producer;
        $this->serializer = $serializer;
    }

    public function execute(RegisterUser $command): void
    {
        $this->users->add($command->user());

        $message = new Message(
            $this->serializer->serialize($command->user(),'json')
        );

        $this->producer->sendEvent('accounts_stream', $message);
    }
}
