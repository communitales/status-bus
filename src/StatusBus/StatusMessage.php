<?php
/**
 * @copyright   Copyright (c) 2020 - 2023 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus;

class StatusMessage
{
    // The message types are mapped to bootstrap color types
    public const TYPE_SUCCESS = 'success';
    public const TYPE_ERROR = 'danger';
    public const TYPE_WARNING = 'warning';
    public const TYPE_INFO = 'info';

    /**
     * Should be one of the type constants.
     */
    private string $type;

    /**
     * i18n message key or actual message if no translator is used.
     */
    private string $messageId;

    /**
     * Optional parameters to generate the i18n message.
     *
     * @var mixed[]
     */
    private array $parameters = [];

    /**
     * This message represents a technical error message.
     */
    private bool $isTechnical;

    /**
     * The message was already displayed
     */
    private bool $isShown = false;

    /**
     * @param string $type Should be one of the type constants.
     * @param string $messageId i18n message key or actual message if no translator is used.
     * @param mixed[] $parameters Optional parameters to generate the i18n message.
     * @param bool $isTechnical This message represents a technical error message.
     */
    public function __construct(
        string $type,
        string $messageId,
        array $parameters = [],
        bool $isTechnical = false
    ) {
        $this->type = $type;
        $this->messageId = $messageId;
        $this->parameters = $parameters;
        $this->isTechnical = $isTechnical;
    }

    /**
     * @param mixed[] $parameters
     */
    public static function createSuccessMessage(string $messageId, array $parameters = []): StatusMessage
    {
        return new self(self::TYPE_SUCCESS, $messageId, $parameters);
    }

    /**
     * @param mixed[] $parameters
     */
    public static function createErrorMessage(
        string $messageId,
        array $parameters = [],
        bool $isTechnical = false
    ): StatusMessage {
        return new self(self::TYPE_ERROR, $messageId, $parameters, $isTechnical);
    }

    /**
     * @param mixed[] $parameters
     */
    public static function createWarningMessage(
        string $messageId,
        array $parameters = [],
        bool $isTechnical = false
    ): StatusMessage {
        return new self(self::TYPE_WARNING, $messageId, $parameters, $isTechnical);
    }

    /**
     * @param mixed[] $parameters
     */
    public static function createInfoMessage(
        string $messageId,
        array $parameters = [],
        bool $isTechnical = false
    ): StatusMessage {
        return new self(self::TYPE_INFO, $messageId, $parameters, $isTechnical);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMessageId(): string
    {
        return $this->messageId;
    }

    /**
     * @return mixed[]
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function isTechnical(): bool
    {
        return $this->isTechnical;
    }

    public function setIsTechnical(bool $isTechnical): void
    {
        $this->isTechnical = $isTechnical;
    }

    public function isShown(): bool
    {
        return $this->isShown;
    }

    public function setIsShown(bool $isShown): void
    {
        $this->isShown = $isShown;
    }
}
