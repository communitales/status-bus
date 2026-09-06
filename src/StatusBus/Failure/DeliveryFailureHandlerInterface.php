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
use Throwable;

interface DeliveryFailureHandlerInterface
{
    public function handleFailure(
        Throwable $exception,
        StatusHandlerInterface $handler,
        StatusMessage $message,
    ): void;
}
