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

use Gears\Event\AbstractEmptyEvent;

/**
 * Event stub class.
 */
class EventStub extends AbstractEmptyEvent
{
    /**
     * Instantiate event.
     *
     * @return self
     */
    public static function instance(): self
    {
        return self::occurred();
    }
}
