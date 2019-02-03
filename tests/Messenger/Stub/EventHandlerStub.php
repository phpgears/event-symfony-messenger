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

namespace Gears\Event\Symfony\Messenger\Tests\Stub;

use Gears\Event\AbstractEventHandler;
use Gears\Event\Event;

/**
 * Event handler stub class.
 */
class EventHandlerStub extends AbstractEventHandler
{
    /**
     * {@inheritdoc}
     */
    protected function getSupportedEventType(): string
    {
        return EventStub::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function handleEvent(Event $event): void
    {
    }
}
