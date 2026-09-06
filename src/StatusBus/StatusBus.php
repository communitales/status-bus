<?php

declare(strict_types=1);

/*
 * SPDX-FileCopyrightText: 2020 Communitales GmbH
 *
 * SPDX-License-Identifier: MIT
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
