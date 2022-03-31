<?php

/**
 * @copyright   Copyright (c) 2020 - 2022 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus\Handler;

use Communitales\Component\StatusBus\StatusMessage;

/**
 * Interface StatusBusHandlerInterface
 */
interface StatusBusHandlerInterface
{

    /**
     * @param StatusMessage $statusMessage
     *
     * @return void
     */
    public function addStatusMessage(StatusMessage $statusMessage): void;

}
