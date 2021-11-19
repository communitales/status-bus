<?php

/**
 * @copyright   Copyright (c) 2020 - 2021 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus;

/**
 * Interface StatusBusInterface
 */
interface StatusBusInterface
{
    /**
     * Normal status of application
     */
    public const STATUS_NORMAL = 'NORMAL';

    /**
     * An action finished successfully
     */
    public const STATUS_SUCCESS = 'SUCCESS';

    /**
     * An action resulted in an error
     */
    public const STATUS_ERROR = 'ERROR';

    /**
     * Send status message to all status bus handlers.
     *
     * @param StatusMessage $statusMessage
     */
    public function addStatusMessage(StatusMessage $statusMessage): void;

    /**
     * @param string  $message
     * @param mixed[] $parameters
     * @param bool    $isTechnical
     */
    public function addError(string $message, array $parameters = [], bool $isTechnical = false): void;

    /**
     * @param string  $message
     * @param mixed[] $parameters
     */
    public function addSuccess(string $message, array $parameters = []): void;

    /**
     * @param string  $message
     * @param mixed[] $parameters
     */
    public function addInfo(string $message, array $parameters = []): void;

    /**
     * @param string  $message
     * @param mixed[] $parameters
     */
    public function addWarning(string $message, array $parameters = []): void;

    /**
     * @return string
     */
    public function getStatus(): string;

}
