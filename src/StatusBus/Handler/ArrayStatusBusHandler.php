<?php

/**
 * @copyright   Copyright (c) 2024 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus\Handler;

use ArrayObject;
use Communitales\Component\StatusBus\StatusMessage;
use Override;

/**
 * Collect all messages in an array.
 * Useful for development or testing.
 */
readonly class ArrayStatusBusHandler implements StatusBusHandlerInterface
{
    /**
     * @param ArrayObject<array-key, StatusMessage> $messageList
     */
    public function __construct(private ArrayObject $messageList)
    {
    }

    #[Override]
    public function addStatusMessage(StatusMessage $statusMessage): void
    {
        $this->messageList->append($statusMessage);
    }
}
