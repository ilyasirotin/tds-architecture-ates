<?php

declare(strict_types=1);

namespace App\Consumer;

use App\Application\Service\TransactionApply;
use App\Application\Service\TransactionApplyUseCase;
use App\Entity\Task;
use App\Entity\Transaction;
use App\Repository\TaskRepository;
use Enqueue\Client\TopicSubscriberInterface;
use Exception;
use Interop\Queue\Context;
use Interop\Queue\Message;
use Interop\Queue\Processor;
use Symfony\Component\Serializer\SerializerInterface;

final class TaskLifecycleConsumer implements Processor, TopicSubscriberInterface
{
    private const EVENT_CREATED = 'task_created';
    private const EVENT_COMPLETED = 'task_completed';
    private const EVENT_SHUFFLED = 'task_shuffled';
    private SerializerInterface $serializer;
    private TransactionApplyUseCase $service;
    private TaskRepository $tasks;

    public function __construct(
        SerializerInterface $serializer,
        TaskRepository $tasks,
        TransactionApplyUseCase $service
    )
    {
        $this->serializer = $serializer;
        $this->service = $service;
        $this->tasks = $tasks;
    }

    public function process(Message $message, Context $context): string
    {
        $event = $message->getProperty('enqueue.topic');
        $payload = $this->serializer->deserialize($message->getBody(), Task::class, 'json');

        $task = $this->tasks->findOneBy(['publicId' => $payload->getPublicId()]);

        // TODO: Need to postpone event processing
        if (!isset($task)) {
            throw new Exception(sprintf('Task can not be found by provided id: %s', $task->getPublicId()));
        }

        switch ($event) {
            case self::EVENT_CREATED:
            case self::EVENT_SHUFFLED:
                $command = new TransactionApply(
                    $task->getAssignee()->getAccount(),
                    Transaction::WITHDRAW,
                    $task->getCost()->getCredit(),
                );
                break;
            case self::EVENT_COMPLETED:
                $command = new TransactionApply(
                    $task->getAssignee()->getAccount(),
                    Transaction::DEPOSIT,
                    $task->getCost()->getDebit()
                );
                break;
            default:
                throw new Exception(sprintf("Unknown event type: %s", $event));
        }

        $this->service->execute($command);

        return self::ACK;
    }

    public static function getSubscribedTopics(): array
    {
        return [
            self::EVENT_CREATED,
            self::EVENT_COMPLETED,
            self::EVENT_SHUFFLED,
        ];
    }
}
