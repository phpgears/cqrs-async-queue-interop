<?php

/*
 * cqrs-async-queue-interop (https://github.com/phpgears/cqrs-async-queue-interop).
 * Queue-interop async decorator for CQRS command bus.
 *
 * @license MIT
 * @link https://github.com/phpgears/cqrs-async-queue-interop
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

declare(strict_types=1);

namespace Gears\CQRS\Async\QueueInterop;

use Gears\CQRS\Async\AbstractCommandQueue;
use Gears\CQRS\Async\Exception\CommandQueueException;
use Gears\CQRS\Async\Serializer\CommandSerializer;
use Gears\CQRS\Command;
use Interop\Queue\Context;
use Interop\Queue\Destination;
use Interop\Queue\Message;
use Interop\Queue\Producer;

class QueueInteropCommandQueue extends AbstractCommandQueue
{
    /**
     * Queue context.
     *
     * @var Context
     */
    protected $context;

    /**
     * @var Destination
     */
    protected $destination;

    /**
     * EnqueueCommandBus constructor.
     *
     * @param CommandSerializer $serializer
     * @param Context           $context
     * @param Destination       $destination
     */
    public function __construct(CommandSerializer $serializer, Context $context, Destination $destination)
    {
        parent::__construct($serializer);

        $this->context = $context;
        $this->destination = $destination;
    }

    /**
     * {@inheritdoc}
     */
    final public function send(Command $command): void
    {
        // @codeCoverageIgnoreStart
        try {
            $this->getMessageProducer()->send($this->destination, $this->getMessage($command));
        } catch (\Exception $exception) {
            throw new CommandQueueException('Failure enqueueing command.', 0, $exception);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Get message from command.
     *
     * @param Command $command
     *
     * @return Message
     */
    protected function getMessage(Command $command): Message
    {
        return $this->context->createMessage($this->getSerializedCommand($command));
    }

    /**
     * Get message producer.
     *
     * @return Producer
     */
    protected function getMessageProducer(): Producer
    {
        return $this->context->createProducer();
    }
}
