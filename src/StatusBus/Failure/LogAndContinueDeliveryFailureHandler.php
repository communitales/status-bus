<?php

declare(strict_types=1);

/*
 * SPDX-FileCopyrightText: 2020 Communitales GmbH
 *
 * SPDX-License-Identifier: MIT
 */

namespace Communitales\Component\StatusBus\Failure;

use Communitales\Component\StatusBus\Handler\StatusHandlerInterface;
use Communitales\Component\StatusBus\StatusMessage;
use Override;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Throwable;

final readonly class LogAndContinueDeliveryFailureHandler implements DeliveryFailureHandlerInterface
{
    public function __construct(private LoggerInterface $logger = new NullLogger())
    {
    }

    #[Override]
    public function handleFailure(
        Throwable $exception,
        StatusHandlerInterface $handler,
        StatusMessage $message,
    ): void {
        try {
            $this->logger->error('Status message delivery failed.', [
                'exception' => $exception,
                'handler' => $handler::class,
                'status_level' => $message->getLevel()->value,
                'status_message' => $message->getMessage(),
            ]);
        } catch (Throwable) {
            // Status delivery must not affect the calling application.
        }
    }
}
