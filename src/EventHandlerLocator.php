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
use Gears\Event\Exception\InvalidEventException;
use Gears\Event\Exception\InvalidEventHandlerException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;

class EventHandlerLocator implements HandlersLocatorInterface
{
    /**
     * Event handlers map.
     *
     * @var mixed[]
     */
    protected $handlersMap;

    /**
     * EventHandlerLocator constructor.
     *
     * @param mixed[] $handlers
     */
    public function __construct(array $handlers)
    {
        $handlers = \array_map(
            function ($handler) {
                if (!\is_array($handler)) {
                    $handler = [$handler];
                }

                return $handler;
            },
            $handlers
        );

        $this->handlersMap = $handlers;
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
                    yield $alias => $seen[] = $handlerCallable;
                }
            }
        }
    }

    /**
     * Get command mapping.
     *
     * @param Envelope $envelope
     *
     * @throws InvalidEventException
     *
     * @return mixed[]
     */
    final protected function getEventMap(Envelope $envelope): array
    {
        $event = $envelope->getMessage();

        if (!$event instanceof Event) {
            throw new InvalidEventException(\sprintf(
                'Event must implement %s interface, %s given',
                Event::class,
                \is_object($event) ? \get_class($event) : \gettype($event)
            ));
        }

        $class = \get_class($event);

        return [$class => $class]
            + ['*' => '*'];
    }
}
