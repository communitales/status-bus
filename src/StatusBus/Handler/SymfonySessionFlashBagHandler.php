<?php
/**
 * @copyright   Copyright (c) 2020 - 2024 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus\Handler;

use Communitales\Component\StatusBus\StatusMessage;
use Override;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class SymfonySessionFlashBagHandler
 */
readonly class SymfonySessionFlashBagHandler implements StatusBusHandlerInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private ?TranslatorInterface $translator = null
    ) {
    }

    #[Override]
    public function addStatusMessage(StatusMessage $statusMessage): void
    {
        $request = $this->requestStack->getCurrentRequest();
        if (!$request instanceof Request) {
            return;
        }

        if (!$request->hasSession()) {
            return;
        }

        $locale = $request->getLocale();

        $message = $statusMessage->getMessage();

        if ($message instanceof TranslatableMessage) {
            if ($this->translator instanceof TranslatorInterface) {
                $message = $message->trans($this->translator, $locale);
            } else {
                $message = $message->getMessage();
            }
        }

        $session = $request->getSession();
        if ($session instanceof Session) {
            $session->getFlashBag()->add($statusMessage->getType(), $message);
        }
    }
}
