<?php

/**
 * @copyright   Copyright (c) 2020 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus;

use RuntimeException;
use Throwable;

/**
 * Exception to catch and display as message.
 * Should contain a german error message.
 */
class MessageException extends RuntimeException
{

    /**
     * @param Throwable $exception
     *
     * @return MessageException
     */
    public static function fromException(Throwable $exception): MessageException
    {
        return new self($exception->getMessage(), (int)$exception->getCode(), $exception);
    }

}
