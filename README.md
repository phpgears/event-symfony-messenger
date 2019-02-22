[![PHP version](https://img.shields.io/badge/PHP-%3E%3D7.1-8892BF.svg?style=flat-square)](http://php.net)
[![Latest Version](https://img.shields.io/packagist/v/phpgears/event-symfony-messenger.svg?style=flat-square)](https://packagist.org/packages/phpgears/event-symfony-messenger)
[![License](https://img.shields.io/github/license/phpgears/event-symfony-messenger.svg?style=flat-square)](https://github.com/phpgears/event-symfony-messenger/blob/master/LICENSE)

[![Build Status](https://img.shields.io/travis/phpgears/event-symfony-messenger.svg?style=flat-square)](https://travis-ci.org/phpgears/event-symfony-messenger)
[![Style Check](https://styleci.io/repos/168998799/shield)](https://styleci.io/repos/168998799)
[![Code Quality](https://img.shields.io/scrutinizer/g/phpgears/event-symfony-messenger.svg?style=flat-square)](https://scrutinizer-ci.com/g/phpgears/event-symfony-messenger)
[![Code Coverage](https://img.shields.io/coveralls/phpgears/event-symfony-messenger.svg?style=flat-square)](https://coveralls.io/github/phpgears/event-symfony-messenger)

[![Total Downloads](https://img.shields.io/packagist/dt/phpgears/event-symfony-messenger.svg?style=flat-square)](https://packagist.org/packages/phpgears/event-symfony-messenger/stats)
[![Monthly Downloads](https://img.shields.io/packagist/dm/phpgears/event-symfony-messenger.svg?style=flat-square)](https://packagist.org/packages/phpgears/event-symfony-messenger/stats)

# Event bus with Symfony's Messenger

Event bus implementation with Symfony's Messenger

## Installation

### Composer

```
composer require phpgears/event-symfony-messenger
```

## Usage

Require composer autoload file

```php
require './vendor/autoload.php';
```

### Events Bus

```php
use Gears\Event\Symfony\Messenger\EventHandlerLocator;
use Gears\Event\Symfony\Messenger\EventBus;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

$eventToHandlerMap = [];

/*
 * IMPORTANT! Events can go through messageBus without being handled, set second argument
 *            on Symfony's EventHandlerLocator constructor ($allowNoHandlers) to true
 */
$handlerLocator = new EventHandlerLocator($eventToHandlerMap, true);
$messengerBus = new MessageBus([new HandleMessageMiddleware($handlerLocator)]);

$eventBus = new EventBus($messengerBus);

/** @var \Gears\Event\Event $event */
$eventBus->handle($event);
```

## Contributing

Found a bug or have a feature request? [Please open a new issue](https://github.com/phpgears/event-symfony-messenger/issues). Have a look at existing issues before.

See file [CONTRIBUTING.md](https://github.com/phpgears/event-symfony-messenger/blob/master/CONTRIBUTING.md)

## License

See file [LICENSE](https://github.com/phpgears/event-symfony-messenger/blob/master/LICENSE) included with the source code for a copy of the license terms.
