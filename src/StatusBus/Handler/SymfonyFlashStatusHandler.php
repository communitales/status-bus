<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2020 - 2026 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus\Handler;

use Communitales\Component\StatusBus\StatusLevel;
use Communitales\Component\StatusBus\StatusMessage;
use Override;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;

final readonly class SymfonyFlashStatusHandler implements StatusHandlerInterface
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    #[Override]
    public function handle(StatusMessage $message): void
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request instanceof Request || !$request->hasSession()) {
            return;
        }

        $session = $request->getSession();
        if (!$session instanceof FlashBagAwareSessionInterface) {
            return;
        }

        $session->getFlashBag()->add(
            $this->getFlashType($message->getLevel()),
            $message->toTranslatableMessage(),
        );
    }

    private function getFlashType(StatusLevel $level): string
    {
        return match ($level) {
            StatusLevel::Error => 'danger',
            StatusLevel::Info => 'info',
            StatusLevel::Success => 'success',
            StatusLevel::Warning => 'warning',
        };
    }
}
