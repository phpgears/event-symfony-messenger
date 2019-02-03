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

use Gears\Event\Symfony\Messenger\EventHandlerLocator;
use Gears\Event\Symfony\Messenger\Tests\Stub\EventHandlerStub;
use Gears\Event\Symfony\Messenger\Tests\Stub\EventStub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;

/**
 * Symfony event messenger wrapper test.
 */
class EventHandlerLocatorTest extends TestCase
{
    /**
     * @expectedException \Gears\Event\Exception\InvalidEventException
     * @expectedExceptionMessage Event must implement Gears\Event\Event interface, stdClass given
     */
    public function testInvalidEvent(): void
    {
        $envelope = new Envelope(new \stdClass());

        foreach ((new EventHandlerLocator([]))->getHandlers($envelope) as $handler) {
            continue;
        }
    }

    /**
     * @expectedException \Gears\Event\Exception\InvalidEventHandlerException
     * @expectedExceptionMessage Event handler must implement Gears\Event\EventHandler interface, string given
     */
    public function testInvalidEventHandler(): void
    {
        $commandMap = [EventStub::class => ['']];
        $envelope = new Envelope(EventStub::instance());

        foreach ((new EventHandlerLocator($commandMap))->getHandlers($envelope) as $handler) {
            continue;
        }
    }

    public function testEventHandler(): void
    {
        $commandHandler = new EventHandlerStub();
        $commandMap = [EventStub::class => $commandHandler];
        $envelope = new Envelope(EventStub::instance());

        foreach ((new EventHandlerLocator($commandMap))->getHandlers($envelope) as $handler) {
            $this->assertInstanceOf(\Closure::class, $handler);
        }
    }
}
