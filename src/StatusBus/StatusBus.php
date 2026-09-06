<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2020 - 2026 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus;

use Communitales\Component\StatusBus\Failure\DeliveryFailureHandlerInterface;
use Communitales\Component\StatusBus\Failure\LogAndContinueDeliveryFailureHandler;
use Communitales\Component\StatusBus\Handler\StatusHandlerInterface;
use Override;
use Throwable;

final readonly class StatusBus implements StatusBusInterface
{
    /**
     * @var list<StatusHandlerInterface>
     */
    private array $handlers;

    /**
     * @param iterable<StatusHandlerInterface> $handlers
     */
    public function __construct(
        iterable $handlers,
        private DeliveryFailureHandlerInterface $failureHandler = new LogAndContinueDeliveryFailureHandler(),
    ) {
        $collectedHandlers = [];
        foreach ($handlers as $handler) {
            $collectedHandlers[] = $handler;
        }

        $this->handlers = $collectedHandlers;
    }

    #[Override]
    public function publish(StatusMessage $message): void
    {
        foreach ($this->handlers as $handler) {
            try {
                $handler->handle($message);
            } catch (Throwable $exception) {
                $this->failureHandler->handleFailure($exception, $handler, $message);
            }
        }
    }
}
