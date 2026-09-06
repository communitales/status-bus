<?php

declare(strict_types=1);

/*
 * SPDX-FileCopyrightText: 2020 Communitales GmbH
 *
 * SPDX-License-Identifier: MIT
 */

namespace Communitales\Component\StatusBus\Handler;

use Communitales\Component\StatusBus\StatusMessage;

interface StatusHandlerInterface
{
    public function handle(StatusMessage $message): void;
}
