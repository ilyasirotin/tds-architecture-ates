<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\Cost;
use App\Entity\Task;
use App\Repository\CostRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Enqueue\Client\ProducerInterface;
use http\Message;
use Symfony\Component\Serializer\SerializerInterface;

final class HandleTaskDataService implements HandleTaskDataUseCase
{
    private TaskRepository $tasks;
    private CostRepository $costs;
    private UserRepository $users;
    private ProducerInterface $producer;
    private SerializerInterface $serializer;

    public function __construct(
        TaskRepository $tasks,
        CostRepository $costs,
        UserRepository $users,
        ProducerInterface $producer,
        SerializerInterface $serializer
    )
    {
        $this->tasks = $tasks;
        $this->costs = $costs;
        $this->users = $users;
        $this->producer = $producer;
        $this->serializer = $serializer;
    }

    public function execute(HandleTaskData $command): Task
    {
        $existingTask = $this->tasks->findOneBy(['publicId' => $command->task()->getPublicId()]);

        if (isset($existingTask)) {
            return $existingTask;
        }

        $newTask = $command->task();

        $author = $this->users->loadUserByIdentifier($newTask->getAuthor()->getUserIdentifier());
        $newTask->setAuthor($author);

        $assignee = $this->users->loadUserByIdentifier($newTask->getAssignee()->getUserIdentifier());
        $newTask->setAssignee($assignee);

        $cost = new Cost();
        $cost->setCredit(sprintf('%d', rand(10, 20)));
        $cost->setDebit(sprintf('%d', rand(20, 40)));

        $newTask->setCost($cost);

        $newTask = $this->tasks->add($newTask);

        // Stream costs data
        $message = new Message(
            $this->serializer->serialize($cost, 'json')
        );

        $this->producer->sendEvent('costs_stream', $message);

        return $newTask;
    }
}
