<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2020 - 2026 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus;

enum StatusLevel: string
{
    case Error = 'error';
    case Info = 'info';
    case Success = 'success';
    case Warning = 'warning';
}
