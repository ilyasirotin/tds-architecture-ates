<?php

declare(strict_types=1);

namespace App\Consumer;

use Enqueue\Client\TopicSubscriberInterface;
use Interop\Queue\Context;
use Interop\Queue\Message;
use Interop\Queue\Processor;
use Symfony\Component\Serializer\SerializerInterface;

final class TaskLifecycleConsumer implements Processor, TopicSubscriberInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function process(Message $message, Context $context): string
    {
        // TODO: Implement task lifecycle consuming

        return self::ACK;
    }

    public static function getSubscribedTopics(): array
    {
        return [
            'task_created',
            'task_completed',
            'task_shuffled'
        ];
    }
}
