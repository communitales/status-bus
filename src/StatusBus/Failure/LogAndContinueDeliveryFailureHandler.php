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
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Throwable;

final readonly class LogAndContinueDeliveryFailureHandler implements DeliveryFailureHandlerInterface
{
    public function __construct(private LoggerInterface $logger = new NullLogger())
    {
    }

    #[Override]
    public function handleFailure(
        Throwable $exception,
        StatusHandlerInterface $handler,
        StatusMessage $message,
    ): void {
        try {
            $this->logger->error('Status message delivery failed.', [
                'exception' => $exception,
                'handler' => $handler::class,
                'status_level' => $message->getLevel()->value,
                'status_message' => $message->getMessage(),
            ]);
        } catch (Throwable) {
            // Status delivery must not affect the calling application.
        }
    }
}
