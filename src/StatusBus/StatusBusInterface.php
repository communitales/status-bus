<?php
/**
 * @copyright   Copyright (c) 2020 - 2024 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus;

use Symfony\Component\Translation\TranslatableMessage;

interface StatusBusInterface
{
    /** Normal status of application */
    public const string STATUS_NORMAL = 'NORMAL';

    /** An action finished successfully */
    public const string STATUS_SUCCESS = 'SUCCESS';

    /** An action resulted in an error */
    public const string STATUS_ERROR = 'ERROR';

    /**
     * Send status message to all status bus handlers.
     */
    public function addStatusMessage(StatusMessage $statusMessage): void;

    public function addError(TranslatableMessage $message): void;

    public function addSuccess(TranslatableMessage $message): void;

    public function addInfo(TranslatableMessage $message): void;

    public function addWarning(TranslatableMessage $message): void;

    public function getStatus(): string;
}
