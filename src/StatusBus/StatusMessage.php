<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2020 - 2026 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus;

use Override;
use Stringable;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final readonly class StatusMessage implements Stringable, TranslatableInterface
{
    /**
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $context
     */
    public function __construct(
        private StatusLevel $level,
        private string $message,
        private array $parameters = [],
        private ?string $domain = null,
        private array $context = [],
    ) {
    }

    #[Override]
    public function __toString(): string
    {
        return $this->message;
    }

    /**
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $context
     */
    public static function error(
        string $message,
        array $parameters = [],
        ?string $domain = null,
        array $context = [],
    ): self {
        return new self(StatusLevel::Error, $message, $parameters, $domain, $context);
    }

    /**
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $context
     */
    public static function info(
        string $message,
        array $parameters = [],
        ?string $domain = null,
        array $context = [],
    ): self {
        return new self(StatusLevel::Info, $message, $parameters, $domain, $context);
    }

    /**
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $context
     */
    public static function success(
        string $message,
        array $parameters = [],
        ?string $domain = null,
        array $context = [],
    ): self {
        return new self(StatusLevel::Success, $message, $parameters, $domain, $context);
    }

    /**
     * @param array<string, mixed> $parameters
     * @param array<string, mixed> $context
     */
    public static function warning(
        string $message,
        array $parameters = [],
        ?string $domain = null,
        array $context = [],
    ): self {
        return new self(StatusLevel::Warning, $message, $parameters, $domain, $context);
    }

    /** @return array<string, mixed> */
    public function getContext(): array
    {
        return $this->context;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function getLevel(): StatusLevel
    {
        return $this->level;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /** @return array<string, mixed> */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function toTranslatableMessage(): TranslatableMessage
    {
        return new TranslatableMessage($this->message, $this->parameters, $this->domain);
    }

    #[Override]
    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $this->toTranslatableMessage()->trans($translator, $locale);
    }
}
