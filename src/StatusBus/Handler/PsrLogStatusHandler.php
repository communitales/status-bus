<?php

declare(strict_types=1);

/*
 * SPDX-FileCopyrightText: 2020 Communitales GmbH
 *
 * SPDX-License-Identifier: MIT
 */

namespace Communitales\Component\StatusBus\Handler;

use Communitales\Component\StatusBus\StatusLevel;
use Communitales\Component\StatusBus\StatusMessage;
use Override;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class PsrLogStatusHandler implements StatusHandlerInterface
{
    public function __construct(
        private LoggerInterface $logger,
        private ?TranslatorInterface $translator = null,
        private ?string $locale = null,
    ) {
    }

    #[Override]
    public function handle(StatusMessage $message): void
    {
        $renderedMessage = $message->getMessage();
        if ($this->translator instanceof TranslatorInterface) {
            $renderedMessage = $message->trans($this->translator, $this->locale);
        }

        $context = $message->getContext();
        $context['status_message'] = [
            'domain' => $message->getDomain(),
            'level' => $message->getLevel()->value,
            'message' => $message->getMessage(),
            'parameters' => $message->getParameters(),
        ];

        $this->logger->log($this->getLogLevel($message->getLevel()), $renderedMessage, $context);
    }

    private function getLogLevel(StatusLevel $level): string
    {
        return match ($level) {
            StatusLevel::Error => LogLevel::ERROR,
            StatusLevel::Info => LogLevel::INFO,
            StatusLevel::Success => LogLevel::NOTICE,
            StatusLevel::Warning => LogLevel::WARNING,
        };
    }
}
