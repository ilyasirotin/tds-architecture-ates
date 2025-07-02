<?php

declare(strict_types=1);

namespace App\Consumer;

use Enqueue\Client\TopicSubscriberInterface;
use Interop\Queue\Context;
use Interop\Queue\Message;
use Interop\Queue\Processor;
use Symfony\Component\Serializer\SerializerInterface;

final class TransactionLifecycleConsumer implements Processor, TopicSubscriberInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function process(Message $message, Context $context): string
    {
        // TODO: Implement transactions consuming

        return self::ACK;
    }

    public static function getSubscribedTopics(): array
    {
        return ['transaction_applied'];
    }
}
