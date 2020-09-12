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

use Gears\Event\Exception\InvalidEventHandlerException;
use Gears\Event\Symfony\Messenger\ContainerAwareEventHandlerLocator;
use Gears\Event\Symfony\Messenger\Tests\Stub\EventHandlerStub;
use Gears\Event\Symfony\Messenger\Tests\Stub\EventStub;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;

/**
 * Symfony event messenger with PSR container wrapper test.
 */
class ContainerAwareEventHandlerLocatorTest extends TestCase
{
    public function testInvalidEventHandler(): void
    {
        $this->expectException(InvalidEventHandlerException::class);
        $this->expectExceptionMessage(
            'Event handler must implement "Gears\Event\EventHandler" interface, "string" given'
        );

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(static::once())
            ->method('get')
            ->with('handler')
            ->willReturn('');
        /* @var ContainerInterface $container */

        $eventMap = [EventStub::class => ['handler']];
        $locator = new ContainerAwareEventHandlerLocator($container, $eventMap);
        $envelope = new Envelope(EventStub::instance());

        foreach ($locator->getHandlers($envelope) as $handler) {
            continue;
        }
    }

    public function testEventHandler(): void
    {
        $eventHandler = new EventHandlerStub();

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects(static::once())
            ->method('get')
            ->with('handler')
            ->willReturn($eventHandler);
        /* @var ContainerInterface $container */

        $event = EventStub::instance();

        $eventMap = [EventStub::class => ['handler']];
        $locator = new ContainerAwareEventHandlerLocator($container, $eventMap);
        $envelope = new Envelope(EventStub::instance());

        foreach ($locator->getHandlers($envelope) as $handler) {
            static::assertInstanceOf(HandlerDescriptor::class, $handler);
            static::assertNull($handler->getHandler()($event));
        }
    }
}
