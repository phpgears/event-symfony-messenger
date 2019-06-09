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

namespace Gears\Event\Symfony\Messenger;

use Gears\Event\Event;
use Gears\Event\EventHandler;
use Gears\Event\Exception\InvalidEventHandlerException;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;

class ContainerAwareEventHandlerLocator extends EventHandlerLocator
{
    /**
     * PSR container.
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * ContainerAwareEventHandlerLocator constructor.
     *
     * @param ContainerInterface $container
     * @param mixed[]            $handlers
     */
    public function __construct(ContainerInterface $container, array $handlers)
    {
        $this->container = $container;

        parent::__construct($handlers);
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidEventHandlerException
     */
    public function getHandlers(Envelope $envelope): iterable
    {
        $seen = [];

        foreach ($this->getEventMap($envelope) as $type) {
            foreach ($this->handlersMap[$type] ?? [] as $alias => $handler) {
                $handler = $this->container->get($handler);

                if (!$handler instanceof EventHandler) {
                    throw new InvalidEventHandlerException(\sprintf(
                        'Event handler must implement %s interface, %s given',
                        EventHandler::class,
                        \is_object($handler) ? \get_class($handler) : \gettype($handler)
                    ));
                }

                $handlerCallable = function (Event $event) use ($handler): void {
                    $handler->handle($event);
                };

                if (!\in_array($handlerCallable, $seen, true)) {
                    $seen[] = $handlerCallable;

                    yield $alias => new HandlerDescriptor($handlerCallable);
                }
            }
        }
    }
}
