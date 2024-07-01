# CHANGELOG

## 2.1.1

* Fix: Fix for `StatusBusInterface`.


## 2.1.0

* Add: Instead of `TranslatableMessage` also `string` is allowed as Parameter for `StatusMessage`.


## 2.0.0 [BC Break]

* Change: Upgrade to PHP 8.3
* Change: [BC] `TranslatableMessage` is now used instead of `$messageId` and `$parameters` as Parameter
  for `StatusMessage`.
* Add: Added some unit tests


## 1.2.0

* Change: Upgrade to PHP 8.2 and improve code quality


## 1.1.1

* Fix: SymfonySessionFlashBagHandler throw error, when current request has no active session


## 1.1.0

* Change: Upgrade to Symfony 6. SymfonySessionFlashBagHandler requires
  now `@Symfony\Component\HttpFoundation\RequestStack` instead of `@session` as first parameter.


## 1.0.3

* Change: ErrorMessage does allow third param for marking technical messages
* Change: Update composer dependencies
* Change: Requires now PHP 8


## 1.0.2

* Revert: StatusBusAwareTrait will not set StatusBus to null without initialization


## 1.0.1

* Change: Allow StatusMessage to be marked as shown
* Fix: StatusBusAwareTrait will set StatusBus to null without initialization


## 1.0.0

* Initial version
