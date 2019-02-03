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

use Gears\Event\Symfony\Messenger\ContainerAwareEventHandlerLocator;
use Gears\Event\Symfony\Messenger\Tests\Stub\EventHandlerStub;
use Gears\Event\Symfony\Messenger\Tests\Stub\EventStub;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Envelope;

/**
 * Symfony event messenger with PSR container wrapper test.
 */
class ContainerAwareEventHandlerLocatorTest extends TestCase
{
    /**
     * @expectedException \Gears\Event\Exception\InvalidEventHandlerException
     * @expectedExceptionMessage Event handler must implement Gears\Event\EventHandler interface, string given
     */
    public function testInvalidEventHandler(): void
    {
        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects($this->once())
            ->method('get')
            ->with('handler')
            ->will($this->returnValue(''));
        /* @var ContainerInterface $container */

        $commandMap = [EventStub::class => ['handler']];
        $locator = new ContainerAwareEventHandlerLocator($container, $commandMap);
        $envelope = new Envelope(EventStub::instance());

        foreach ($locator->getHandlers($envelope) as $handler) {
            continue;
        }
    }

    public function testEventHandler(): void
    {
        $commandHandler = new EventHandlerStub();

        $container = $this->getMockBuilder(ContainerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $container->expects($this->once())
            ->method('get')
            ->with('handler')
            ->will($this->returnValue($commandHandler));
        /* @var ContainerInterface $container */

        $commandMap = [EventStub::class => ['handler']];
        $locator = new ContainerAwareEventHandlerLocator($container, $commandMap);
        $envelope = new Envelope(EventStub::instance());

        foreach ($locator->getHandlers($envelope) as $handler) {
            $this->assertInstanceOf(\Closure::class, $handler);
        }
    }
}
