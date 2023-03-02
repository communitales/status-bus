<?php
/**
 * @copyright   Copyright (c) 2020 - 2023 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus\Handler;

use Communitales\Component\StatusBus\StatusMessage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class SymfonySessionFlashBagHandler
 */
class SymfonySessionFlashBagHandler implements StatusBusHandlerInterface
{
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    /**
     * @var TranslatorInterface|null
     */
    private ?TranslatorInterface $translator = null;

    /**
     * @var string
     */
    private string $technicalMessage = 'The support has been informed.';

    /**
     * @var string
     */
    private string $technicalMessageI18nKey = 'status_message.technical_message';

    /**
     * @param RequestStack $requestStack
     * @param TranslatorInterface|null $translator
     */
    public function __construct(RequestStack $requestStack, ?TranslatorInterface $translator)
    {
        $this->requestStack = $requestStack;
        $this->translator = $translator;
    }

    /**
     * @param string $technicalMessage
     *
     * @return void
     */
    public function setTechnicalMessage(string $technicalMessage): void
    {
        $this->technicalMessage = $technicalMessage;
    }

    /**
     * @param string $technicalMessageI18nKey
     *
     * @return void
     */
    public function setTechnicalMessageI18nKey(string $technicalMessageI18nKey): void
    {
        $this->technicalMessageI18nKey = $technicalMessageI18nKey;
    }

    /**
     * @param StatusMessage $statusMessage
     *
     * @return void
     */
    public function addStatusMessage(StatusMessage $statusMessage): void
    {
        if ($this->translator !== null) {
            // with i18n
            $message = $this->translator->trans($statusMessage->getMessageId(), $statusMessage->getParameters());
            if ($statusMessage->isTechnical()) {
                $message .= ' '.$this->translator->trans($this->technicalMessageI18nKey);
            }
        } else {
            // without i18n
            $message = $statusMessage->getMessageId();
            if ($statusMessage->isTechnical()) {
                $message .= ' '.$this->technicalMessage;
            }
        }

        $request = $this->requestStack->getCurrentRequest();
        if (!$request instanceof Request) {
            return;
        }

        if (!$request->hasSession()) {
            return;
        }

        $session = $request->getSession();
        if ($session instanceof Session) {
            $session->getFlashBag()->add($statusMessage->getType(), $message);
        }
    }
}
