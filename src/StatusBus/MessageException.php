<?php

/**
 * @copyright Copyright (c) 2020 - 2026 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus;

use RuntimeException;
use Throwable;

/**
 * Exception to catch and display as message.
 */
class MessageException extends RuntimeException
{
    public function __construct(public readonly StatusMessage $statusMessage, ?Throwable $exception = null)
    {
        if ($exception instanceof Throwable) {
            parent::__construct($exception->getMessage(), (int)$exception->getCode(), $exception);
        }
    }
}
