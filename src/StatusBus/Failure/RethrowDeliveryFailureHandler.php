<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2020 - 2026 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus\Failure;

use Communitales\Component\StatusBus\Handler\StatusHandlerInterface;
use Communitales\Component\StatusBus\StatusMessage;
use Override;
use Throwable;

final readonly class RethrowDeliveryFailureHandler implements DeliveryFailureHandlerInterface
{
    #[Override]
    public function handleFailure(
        Throwable $exception,
        StatusHandlerInterface $handler,
        StatusMessage $message,
    ): never {
        throw $exception;
    }
}
