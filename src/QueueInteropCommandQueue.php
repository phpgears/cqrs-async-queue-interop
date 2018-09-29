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
use Interop\Queue\PsrContext;
use Interop\Queue\PsrDestination;
use Interop\Queue\PsrMessage;

class QueueInteropCommandQueue extends AbstractCommandQueue
{
    /**
     * Queue context.
     *
     * @var PsrContext
     */
    private $context;

    /**
     * @var PsrDestination
     */
    private $destination;

    /**
     * EnqueueCommandBus constructor.
     *
     * @param CommandSerializer $serializer
     * @param PsrContext        $context
     * @param PsrDestination    $destination
     */
    public function __construct(CommandSerializer $serializer, PsrContext $context, PsrDestination $destination)
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
            $this->context->createProducer()->send($this->destination, $this->getQueueMessage($command));
        } catch (\Exception $exception) {
            throw new CommandQueueException('Failure enqueueing command', 0, $exception);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * Get queue message from command.
     *
     * @param Command $command
     *
     * @return PsrMessage
     */
    protected function getQueueMessage(Command $command): PsrMessage
    {
        return $this->context->createMessage($this->getSerializedCommand($command));
    }
}
