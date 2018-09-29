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
use Interop\Queue\PsrContext;
use Interop\Queue\PsrDestination;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProducer;
use PHPUnit\Framework\TestCase;

class QueueInteropCommandQueueTest extends TestCase
{
    public function testQueueSend(): void
    {
        $serializer = $this->getMockBuilder(CommandSerializer::class)
            ->getMock();
        $serializer->expects($this->once())
            ->method('serialize')
            ->will($this->returnValue(''));
        /* @var CommandSerializer $serializer */

        $producer = $this->getMockBuilder(PsrProducer::class)
            ->getMock();
        $producer->expects($this->once())
            ->method('send');
        /* @var PsrProducer $producer */

        /* @var PsrMessage $message */
        $message = $this->getMockBuilder(PsrMessage::class)
            ->getMock();

        $context = $this->getMockBuilder(PsrContext::class)
            ->getMock();
        $context->expects($this->once())
            ->method('createProducer')
            ->will($this->returnValue($producer));
        $context->expects($this->once())
            ->method('createMessage')
            ->will($this->returnValue($message));
        /* @var PsrContext $context */

        /* @var PsrDestination $destination */
        $destination = $this->getMockBuilder(PsrDestination::class)
            ->getMock();

        /* @var Command $command */
        $command = $this->getMockBuilder(Command::class)
            ->getMock();

        (new QueueInteropCommandQueue($serializer, $context, $destination))->send($command);
    }
}
