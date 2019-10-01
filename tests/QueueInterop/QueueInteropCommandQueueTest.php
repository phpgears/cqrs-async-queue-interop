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

namespace Gears\CQRS\Async\QueueInterop\Tests;

use Gears\CQRS\Async\QueueInterop\QueueInteropCommandQueue;
use Gears\CQRS\Async\Serializer\CommandSerializer;
use Gears\CQRS\Command;
use Interop\Queue\Context;
use Interop\Queue\Destination;
use Interop\Queue\Message;
use Interop\Queue\Producer;
use PHPUnit\Framework\TestCase;

class QueueInteropCommandQueueTest extends TestCase
{
    public function testQueueSend(): void
    {
        $serializer = $this->getMockBuilder(CommandSerializer::class)
            ->getMock();
        $serializer->expects(static::once())
            ->method('serialize')
            ->will(static::returnValue(''));
        /* @var CommandSerializer $serializer */

        $producer = $this->getMockBuilder(Producer::class)
            ->getMock();
        $producer->expects(static::once())
            ->method('send');
        /* @var Producer $producer */

        /* @var Message $message */
        $message = $this->getMockBuilder(Message::class)
            ->getMock();

        $context = $this->getMockBuilder(Context::class)
            ->getMock();
        $context->expects(static::once())
            ->method('createProducer')
            ->will(static::returnValue($producer));
        $context->expects(static::once())
            ->method('createMessage')
            ->will(static::returnValue($message));
        /* @var Context $context */

        /* @var Destination $destination */
        $destination = $this->getMockBuilder(Destination::class)
            ->getMock();

        /* @var Command $command */
        $command = $this->getMockBuilder(Command::class)
            ->getMock();

        (new QueueInteropCommandQueue($serializer, $context, $destination))->send($command);
    }
}
