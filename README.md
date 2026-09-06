# Communitales StatusBus Component

Publish status messages without coupling application code to their presentation.
Messages can be sent to a PSR-3 logger, a Symfony session flash bag, or any custom
handler.

Status delivery is synchronous and best-effort. A failing handler does not affect
the calling application or prevent other handlers from receiving the message.

## Installation

```bash
composer require communitales/status-bus
```

## Usage

Inject `StatusBusInterface` and publish an immutable `StatusMessage`:

```php
use Communitales\Component\StatusBus\StatusBusInterface;
use Communitales\Component\StatusBus\StatusMessage;

final readonly class CreateOrder
{
    public function __construct(private StatusBusInterface $statusBus)
    {
    }

    public function __invoke(string $orderNumber): void
    {
        // Application code ...

        $this->statusBus->publish(StatusMessage::success(
            message: 'order.created',
            parameters: ['%order%' => $orderNumber],
            domain: 'status',
            context: ['order_number' => $orderNumber],
        ));
    }
}
```

`message` can be either a literal message or a translation key. The available
factory methods are `success()`, `info()`, `warning()`, and `error()`.

Translation parameters and the translation domain are presentation data.
`context` contains structured diagnostic data for handlers such as the PSR-3
logger and is not shown in flash messages.

## Symfony configuration

The following configuration sends every status message to the application logger
and to the Symfony flash bag:

```yaml
# config/services.yaml
services:
    _defaults:
        autowire: true
        autoconfigure: true

    Communitales\Component\StatusBus\StatusBusInterface:
        alias: Communitales\Component\StatusBus\StatusBus

    Communitales\Component\StatusBus\StatusBus:
        arguments:
            $handlers: !tagged_iterator communitales.status_handler

    Communitales\Component\StatusBus\Failure\DeliveryFailureHandlerInterface:
        alias: Communitales\Component\StatusBus\Failure\LogAndContinueDeliveryFailureHandler

    Communitales\Component\StatusBus\Failure\LogAndContinueDeliveryFailureHandler:
        arguments:
            $logger: '@logger'

    Communitales\Component\StatusBus\Handler\PsrLogStatusHandler:
        tags: ['communitales.status_handler']

    Communitales\Component\StatusBus\Handler\SymfonyFlashStatusHandler:
        tags: ['communitales.status_handler']
```

Remove either handler registration if only one destination is required.

The log handler maps status levels as follows:

| Status | PSR-3 level |
|---|---|
| `success` | `notice` |
| `info` | `info` |
| `warning` | `warning` |
| `error` | `error` |

When a translator is injected, log messages are translated using the optional
locale configured on `PsrLogStatusHandler`. The original message, domain,
parameters, and level remain available below the `status_message` context key.

The flash handler stores a `TranslatableMessage` instead of rendering it early.
Translate the message when displaying it:

```twig
{% for type, messages in app.flashes %}
    {% for message in messages %}
        <div class="alert alert-{{ type }}">{{ message|trans }}</div>
    {% endfor %}
{% endfor %}
```

The flash handler performs the presentation-specific mapping from status `error`
to Bootstrap flash type `danger`.

## Delivery failures

`LogAndContinueDeliveryFailureHandler` is the production strategy. It reports a
failed delivery through a PSR-3 logger and lets the bus continue with the next
handler. It also suppresses logger failures, preserving fire-and-forget behavior.

Use `RethrowDeliveryFailureHandler` in development or tests when handler failures
should fail immediately:

```php
use Communitales\Component\StatusBus\Failure\RethrowDeliveryFailureHandler;
use Communitales\Component\StatusBus\StatusBus;

$statusBus = new StatusBus($handlers, new RethrowDeliveryFailureHandler());
```

`InMemoryStatusHandler` is available for tests and exposes received messages via
`messages()`.

## Custom handlers

```php
use Communitales\Component\StatusBus\Handler\StatusHandlerInterface;
use Communitales\Component\StatusBus\StatusMessage;

final class CustomStatusHandler implements StatusHandlerInterface
{
    public function handle(StatusMessage $message): void
    {
        // Deliver the message to another destination.
    }
}
```

Handlers should throw when delivery fails. `StatusBus` applies the configured
failure strategy and decides whether processing continues.
