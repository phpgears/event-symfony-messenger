<?php

/*
 * event-symfony-messenger (https://github.com/phpgears/event-symfony-messenger).
 * Event bus with Symfony's Messenger.
 *
 * @license MIT
 * @link https://github.com/phpgears/event-symfony-messenger
 * @author Julián Gutiérrez <juliangut@gmail.com>
 */

declare(strict_types=1);

namespace Gears\Event\Symfony\Messenger\Tests;

use Gears\Event\Symfony\Messenger\EventBus;
use Gears\Event\Symfony\Messenger\Tests\Stub\EventStub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Symfony event bus test.
 */
class EventBusTest extends TestCase
{
    public function testHandling(): void
    {
        $messengerMock = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $messengerMock->expects(static::once())
            ->method('dispatch')
            ->willReturn(new Envelope(EventStub::instance()));
        /* @var MessageBusInterface $messengerMock */

        (new EventBus($messengerMock))->dispatch(EventStub::instance());
    }
}
