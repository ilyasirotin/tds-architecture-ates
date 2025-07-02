<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Enqueue\Client\Message;
use Enqueue\Client\ProducerInterface;
use Exception;
use Symfony\Component\Serializer\SerializerInterface;

final class CompleteTaskService implements CompleteTaskUseCase
{
    private TaskRepository $tasks;
    private ProducerInterface $producer;
    private SerializerInterface $serializer;

    public function __construct(
        TaskRepository $tasks,
        ProducerInterface $producer,
        SerializerInterface $serializer
    )
    {
        $this->tasks = $tasks;
        $this->producer = $producer;
        $this->serializer = $serializer;
    }

    public function execute(CompleteTask $command): Task
    {
        $task = $this->tasks->find($command->taskId());

        if (!isset($task)) {
            // TODO: Properly handle exception
            throw new Exception('Can not find requested task');
        }

        $task->complete();
        $completedTask = $this->tasks->update($task);

        $message = new Message(
            $this->serializer->serialize($completedTask, 'json')
        );

        // Produce task events
        $this->producer->sendEvent('tasks_stream', $message);
        $this->producer->sendEvent('task_completed', $message);

        return $completedTask;
    }
}
