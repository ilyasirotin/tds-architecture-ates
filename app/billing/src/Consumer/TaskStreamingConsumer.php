<?php

declare(strict_types=1);

namespace App\Consumer;

use App\Application\Service\HandleTaskData;
use App\Application\Service\HandleTaskDataUseCase;
use App\Entity\Task;
use Enqueue\Client\TopicSubscriberInterface;
use Interop\Queue\Context;
use Interop\Queue\Message;
use Interop\Queue\Processor;
use Symfony\Component\Serializer\SerializerInterface;

final class TaskStreamingConsumer implements Processor, TopicSubscriberInterface
{
    private SerializerInterface $serializer;
    private HandleTaskDataUseCase $service;

    public function __construct(SerializerInterface $serializer, HandleTaskDataUseCase $service)
    {
        $this->serializer = $serializer;
        $this->service = $service;
    }

    public function process(Message $message, Context $context)
    {
        $payload = $message->getBody();
        $task = $this->serializer->deserialize($payload, Task::class, 'json');

        $this->service->execute(
            new HandleTaskData($task)
        );

        return self::ACK;
    }

    public static function getSubscribedTopics(): array
    {
        return ['tasks_stream'];
    }
}
