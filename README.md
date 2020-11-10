# Communitales StatusBus Component

Send status messages to a central status bus. 

The content of the status can be displayed in the UI or logged into a file.
So any class of the application is able to send messages to the UI.

## Setup

```
composer require communitales/status-bus
```

This package is framework-less and contains only interfaces and abstract classes.
You may also install a framework specific version.

```
composer require communitales/status-bus-symfony
```


## Usage

You can send messages to the status 

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
