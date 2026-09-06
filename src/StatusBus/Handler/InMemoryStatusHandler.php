<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2020 - 2026 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
