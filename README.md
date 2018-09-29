[![PHP version](https://img.shields.io/badge/PHP-%3E%3D7.1-8892BF.svg?style=flat-square)](http://php.net)
[![Latest Version](https://img.shields.io/packagist/v/phpgears/cqrs-async-queue-interop.svg?style=flat-square)](https://packagist.org/packages/phpgears/cqrs-async-queue-interop)
[![License](https://img.shields.io/github/license/phpgears/cqrs-async-queue-interop.svg?style=flat-square)](https://github.com/phpgears/cqrs-async-queue-interop/blob/master/LICENSE)

[![Build Status](https://img.shields.io/travis/phpgears/cqrs-async-queue-interop.svg?style=flat-square)](https://travis-ci.org/phpgears/cqrs-async-queue-interop)
[![Style Check](https://styleci.io/repos/150907907/shield)](https://styleci.io/repos/150907907)
[![Code Quality](https://img.shields.io/scrutinizer/g/phpgears/cqrs-async-queue-interop.svg?style=flat-square)](https://scrutinizer-ci.com/g/phpgears/cqrs-async-queue-interop)
[![Code Coverage](https://img.shields.io/coveralls/phpgears/cqrs-async-queue-interop.svg?style=flat-square)](https://coveralls.io/github/phpgears/cqrs-async-queue-interop)

[![Total Downloads](https://img.shields.io/packagist/dt/phpgears/cqrs-async-queue-interop.svg?style=flat-square)](https://packagist.org/packages/phpgears/cqrs-async-queue-interop/stats)
[![Monthly Downloads](https://img.shields.io/packagist/dm/phpgears/cqrs-async-queue-interop.svg?style=flat-square)](https://packagist.org/packages/phpgears/cqrs-async-queue-interop/stats)

# Queue-interop async CQRS

[Queue-interop](https://github.com/queue-interop/queue-interop) async decorator for CQRS command bus

## Installation

### Composer

```
composer require phpgears/cqrs-async-queue-interop
```

## Usage

Require composer autoload file

```php
require './vendor/autoload.php';
```

### Asynchronous Commands Bus

Please review [phpgears/cqrs-async](https://github.com/phpgears/cqrs-async) for more information on async command bus

```php
use Gears\CQRS\Async\AsyncCommandBus;
use Gears\CQRS\Async\QueueInterop\QueueInteropCommandQueue;
use Gears\CQRS\Async\Serializer\JsonCommandSerializer;
use Gears\CQRS\Async\Discriminator\ParameterCommandDiscriminator;

/* @var \Gears\CQRS\CommandBus $commandBus */
/* @var \Interop\Queue\PsrContext $context */
/* @var \Interop\Queue\PsrDestination $destination */

$commandQueue = new QueueInteropCommandQueue(new JsonCommandSerializer(), $context, $destination);

$asyncCommandBus new AsyncCommandBus(
    $commandBus,
    $commandQueue,
    new ParameterCommandDiscriminator('async')
);

$asyncCommand = new CustomCommand(['async' => true]);

$asyncCommandBus->handle($asyncCommand);
```

There are some queue-interop implementations available such as [Enqueue](https://github.com/php-enqueue/enqueue) which supports an incredible number of message queues

## Contributing

Found a bug or have a feature request? [Please open a new issue](https://github.com/phpgears/cqrs-async-queue-interop/issues). Have a look at existing issues before.

See file [CONTRIBUTING.md](https://github.com/phpgears/cqrs-async-queue-interop/blob/master/CONTRIBUTING.md)

## License

See file [LICENSE](https://github.com/phpgears/cqrs-async-queue-interop/blob/master/LICENSE) included with the source code for a copy of the license terms.
