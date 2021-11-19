<?php

/**
 * @copyright   Copyright (c) 2020 - 2021 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus;

/**
 * Class StatusBusAwareInterface
 */
interface StatusBusAwareInterface
{

    /**
     * @param StatusBusInterface $statusBus
     */
    public function setStatusBus(StatusBusInterface $statusBus): void;
}
