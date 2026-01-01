<?php

/**
 * @copyright Copyright (c) 2020 - 2026 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus;

use Communitales\Component\Log\LogAwareTrait;
use Communitales\Component\StatusBus\Handler\StatusBusHandlerInterface;
use IteratorAggregate;
use Override;
use Psr\Log\LoggerAwareInterface;
use Symfony\Component\Translation\TranslatableMessage;
use Throwable;

/**
 * Class StatusBus
 */
class StatusBus implements LoggerAwareInterface, StatusBusInterface
{
    use LogAwareTrait;

    /**
     * The status
     */
    private string $status = self::STATUS_NORMAL;

    /**
     * @var StatusBusHandlerInterface[]
     */
    private array $statusBusHandlers = [];

    /**
     * @param IteratorAggregate<StatusBusHandlerInterface> $statusBusHandlers
     */
    public function __construct(iterable $statusBusHandlers)
    {
        try {
            foreach ($statusBusHandlers->getIterator() as $statusBusHandler) {
                $this->addStatusBusHandler($statusBusHandler);
            }
        } catch (Throwable $throwable) {
            $this->logException($throwable);
        }
    }

    public function addStatusBusHandler(StatusBusHandlerInterface $statusBusHandler): void
    {
        $this->statusBusHandlers[] = $statusBusHandler;
    }

    /**
     * Send status message to all status bus handlers.
     */
    #[Override]
    public function addStatusMessage(StatusMessage $statusMessage): void
    {
        // If the status message is already shown, do not show again
        if ($statusMessage->isShown()) {
            return;
        }

        // Send status message to all handlers
        foreach ($this->statusBusHandlers as $statusBusHandler) {
            $statusBusHandler->addStatusMessage($statusMessage);
        }

        // Mark status message as shown
        $statusMessage->setIsShown(true);
    }

    #[Override]
    public function addError(TranslatableMessage|string $message): void
    {
        $this->setStatus(self::STATUS_ERROR);
        $this->addStatusMessage(StatusMessage::createErrorMessage($message));
    }

    #[Override]
    public function addSuccess(TranslatableMessage|string $message): void
    {
        $this->setStatus(self::STATUS_SUCCESS);
        $this->addStatusMessage(StatusMessage::createSuccessMessage($message));
    }

    #[Override]
    public function addInfo(TranslatableMessage|string $message): void
    {
        $this->setStatus(self::STATUS_NORMAL);
        $this->addStatusMessage(StatusMessage::createInfoMessage($message));
    }

    #[Override]
    public function addWarning(TranslatableMessage|string $message): void
    {
        $this->setStatus(self::STATUS_NORMAL);
        $this->addStatusMessage(StatusMessage::createWarningMessage($message));
    }

    #[Override]
    public function getStatus(): string
    {
        return $this->status;
    }

    private function setStatus(string $status): void
    {
        switch ($status) {
            case self::STATUS_NORMAL:
                // Keep current status
                break;
            case self::STATUS_SUCCESS:
                // If an error occurred, the status will not change
                if ($this->status !== self::STATUS_ERROR) {
                    $this->status = self::STATUS_SUCCESS;
                }

                break;
            case self::STATUS_ERROR:
                // Set status to error
                $this->status = self::STATUS_ERROR;
                break;
        }
    }
}
