<?php
/**
 * @copyright   Copyright (c) 2020 - 2023 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus;

use Communitales\Component\Log\LogAwareTrait;
use Communitales\Component\StatusBus\Handler\StatusBusHandlerInterface;
use Exception;
use IteratorAggregate;
use Psr\Log\LoggerAwareInterface;

/**
 * Class StatusBus
 */
class StatusBus implements StatusBusInterface, LoggerAwareInterface
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
        } catch (Exception $exception) {
            $this->logException($exception);
        }
    }

    public function addStatusBusHandler(StatusBusHandlerInterface $statusBusHandler): void
    {
        $this->statusBusHandlers[] = $statusBusHandler;
    }

    /**
     * Send status message to all status bus handlers.
     */
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

    /**
     * @param mixed[] $parameters
     */
    public function addError(string $message, array $parameters = [], bool $isTechnical = false): void
    {
        $this->setStatus(self::STATUS_ERROR);
        $this->addStatusMessage(StatusMessage::createErrorMessage($message, $parameters, $isTechnical));
    }

    /**
     * @param mixed[] $parameters
     */
    public function addSuccess(string $message, array $parameters = []): void
    {
        $this->setStatus(self::STATUS_SUCCESS);
        $this->addStatusMessage(StatusMessage::createSuccessMessage($message, $parameters));
    }

    /**
     * @param mixed[] $parameters
     */
    public function addInfo(string $message, array $parameters = []): void
    {
        $this->setStatus(self::STATUS_NORMAL);
        $this->addStatusMessage(StatusMessage::createInfoMessage($message, $parameters));
    }

    /**
     * @param mixed[] $parameters
     */
    public function addWarning(string $message, array $parameters = []): void
    {
        $this->setStatus(self::STATUS_NORMAL);
        $this->addStatusMessage(StatusMessage::createWarningMessage($message, $parameters));
    }

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
