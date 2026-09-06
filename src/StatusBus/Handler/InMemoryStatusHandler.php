<?php

declare(strict_types=1);

/*
 * SPDX-FileCopyrightText: 2020 Communitales GmbH
 *
 * SPDX-License-Identifier: MIT
 */

namespace Communitales\Component\StatusBus\Handler;

use Communitales\Component\StatusBus\StatusMessage;
use Override;

final class InMemoryStatusHandler implements StatusHandlerInterface
{
    /** @var list<StatusMessage> */
    private array $messages = [];

    #[Override]
    public function handle(StatusMessage $message): void
    {
        $this->messages[] = $message;
    }

    /** @return list<StatusMessage> */
    public function messages(): array
    {
        return $this->messages;
    }
}
