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
use Gears\Event\EventBus as EventBusInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

final class EventBus implements EventBusInterface
{
    /**
     * Wrapped message bus.
     *
     * @var MessageBusInterface
     */
    private $wrappedMessageBus;

    /**
     * EventBus constructor.
     *
     * @param MessageBusInterface $wrappedMessageBus
     */
    public function __construct(MessageBusInterface $wrappedMessageBus)
    {
        $this->wrappedMessageBus = $wrappedMessageBus;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch(Event $event): void
    {
        $this->wrappedMessageBus->dispatch(new Envelope($event));
    }
}
