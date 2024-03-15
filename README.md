# Communitales StatusBus Component

Send status messages to a central status bus. 

The content of the status can be displayed in the UI or logged into a file.
So any service class of the application is able to send messages to the UI.


## Setup

```
composer require communitales/status-bus
```

Setup for Symfony in `services.yaml`:

```
services:

    _defaults:
        bind:
            iterable $statusBusHandlers: !tagged_iterator communitales.status_handler

    _instanceof:
        Communitales\Component\StatusBus\StatusBusAwareInterface:
            calls:
                - [setStatusBus, ['@Communitales\Component\StatusBus\StatusBus']]

    Communitales\Component\StatusBus\Handler\SymfonySessionFlashBagHandler:
        tags: ['communitales.status_handler']

    Communitales\Component\StatusBus\StatusBus: ~

```

## Usage

You can send messages to the StatusBus

```

use Communitales\Component\StatusBus\StatusBusAwareInterface;
use Communitales\Component\StatusBus\StatusBusAwareTrait;

class MyClass implements StatusBusAwareInterface
{
    use StatusBusAwareTrait;

    public function doSomething() {

        // ...

        // You can use the status bus without i18n
        $this->statusBus->addSuccess('The item "example" has been successfully created.');

        // And you can use the status bus with i18n
        $this->statusBus->addSuccess('action_item.success', ['item_name' => 'example']);
    }
}

```
