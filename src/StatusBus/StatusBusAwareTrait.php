<?php

/**
 * @copyright   Copyright (c) 2020 - 2021 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus;

/**
 * Trait StatusBusAwareTrait
 */
trait StatusBusAwareTrait
{

    /**
     * @var StatusBusInterface
     */
    protected StatusBusInterface $statusBus;

    /**
     * @param StatusBusInterface $statusBus
     */
    public function setStatusBus(StatusBusInterface $statusBus): void
    {
        $this->statusBus = $statusBus;
    }

}
