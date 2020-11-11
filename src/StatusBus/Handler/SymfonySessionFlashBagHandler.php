<?php

/**
 * @copyright   Copyright (c) 2020 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus\Handler;

use Communitales\Component\StatusBus\StatusMessage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class SymfonySessionFlashBagHandler
 */
class SymfonySessionFlashBagHandler implements StatusBusHandlerInterface
{

    /**
     * @var Session
     */
    private Session $session;

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
     * @param Session                  $session
     * @param TranslatorInterface|null $translator
     */
    public function __construct(Session $session, ?TranslatorInterface $translator = null)
    {
        $this->session = $session;
        $this->translator = $translator;
    }

    /**
     * @param string $technicalMessage
     */
    public function setTechnicalMessage(string $technicalMessage): void
    {
        $this->technicalMessage = $technicalMessage;
    }

    /**
     * @param string $technicalMessageI18nKey
     */
    public function setTechnicalMessageI18nKey(string $technicalMessageI18nKey): void
    {
        $this->technicalMessageI18nKey = $technicalMessageI18nKey;
    }

    /**
     * @param StatusMessage $statusMessage
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

        $this->session->getFlashBag()->add($statusMessage->getType(), $message);
    }

}
