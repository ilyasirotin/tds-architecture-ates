<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Enqueue\Client\Message;
use Enqueue\Client\ProducerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class ShuffleTasksService implements ShuffleTasksUseCase
{
    private TaskRepository $task;
    private UserRepository $users;
    private SerializerInterface $serializer;
    private ProducerInterface $producer;

    public function __construct(
        TaskRepository $task,
        UserRepository $users,
        SerializerInterface $serializer,
        ProducerInterface $producer,
    )
    {
        $this->task = $task;
        $this->users = $users;
        $this->serializer = $serializer;
        $this->producer = $producer;
    }

    public function execute(ShuffleTasks $command): Task
    {
        // TODO: To be implemented

        /**
         * Seems like a batch processing is most suitable here.
         * Consider the next simple flow:
         *  1. Retrieve a bunch of currently opened tasks;
         *  2. Find a new suitable popug-worker here;
         *  3. Assign the task to new popug;
         *  4. Produce new event to the broker.
         */

        // Just a stub
        $shuffledTask = new Task();

        $message = new Message(
            $this->serializer->serialize($shuffledTask, 'json')
        );

        // Produce event to broker
        $this->producer->sendEvent('tasks_stream', $message);
        $this->producer->sendEvent('task_shuffled', $message);

        return $shuffledTask;
    }
}
