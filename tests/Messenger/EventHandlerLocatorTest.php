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

use Gears\Event\Exception\InvalidEventException;
use Gears\Event\Exception\InvalidEventHandlerException;
use Gears\Event\Symfony\Messenger\EventHandlerLocator;
use Gears\Event\Symfony\Messenger\Tests\Stub\EventHandlerStub;
use Gears\Event\Symfony\Messenger\Tests\Stub\EventStub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;

/**
 * Symfony event messenger wrapper test.
 */
class EventHandlerLocatorTest extends TestCase
{
    public function testInvalidEvent(): void
    {
        $this->expectException(InvalidEventException::class);
        $this->expectExceptionMessage('Event must implement "Gears\Event\Event" interface, "stdClass" given');

        $envelope = new Envelope(new \stdClass());

        foreach ((new EventHandlerLocator([]))->getHandlers($envelope) as $handler) {
            continue;
        }
    }

    public function testInvalidEventHandler(): void
    {
        $this->expectException(InvalidEventHandlerException::class);
        $this->expectExceptionMessage(
            'Event handler must implement "Gears\Event\EventHandler" interface, "string" given'
        );

        $eventMap = [EventStub::class => ['']];
        $envelope = new Envelope(EventStub::instance());

        foreach ((new EventHandlerLocator($eventMap))->getHandlers($envelope) as $handler) {
            continue;
        }
    }

    public function testEventHandler(): void
    {
        $eventHandler = new EventHandlerStub();
        $eventMap = [EventStub::class => $eventHandler];
        $envelope = new Envelope(EventStub::instance());

        foreach ((new EventHandlerLocator($eventMap))->getHandlers($envelope) as $handler) {
            static::assertInstanceOf(HandlerDescriptor::class, $handler);
            static::assertNull($handler->getHandler()(EventStub::instance()));
        }
    }
}
