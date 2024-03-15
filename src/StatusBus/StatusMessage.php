<?php
/**
 * @copyright   Copyright (c) 2020 - 2024 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Component\StatusBus;

use Symfony\Component\Translation\TranslatableMessage;

class StatusMessage
{
    // The message types are mapped to bootstrap color types
    public const string TYPE_SUCCESS = 'success';

    public const string TYPE_ERROR = 'danger';

    public const string TYPE_WARNING = 'warning';

    public const string TYPE_INFO = 'info';

    /**
     * The message was already sent to the StatusBusHandlers
     */
    private bool $isShown = false;

    /**
     * @param string $type Should be one of the type constants.
     */
    public function __construct(private readonly string $type, private readonly TranslatableMessage $message)
    {
    }

    public static function createSuccessMessage(TranslatableMessage $message): StatusMessage
    {
        return new self(self::TYPE_SUCCESS, $message);
    }

    public static function createErrorMessage(TranslatableMessage $message): StatusMessage
    {
        return new self(self::TYPE_ERROR, $message);
    }

    public static function createWarningMessage(TranslatableMessage $message): StatusMessage
    {
        return new self(self::TYPE_WARNING, $message);
    }

    public static function createInfoMessage(TranslatableMessage $message): StatusMessage
    {
        return new self(self::TYPE_INFO, $message);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMessage(): TranslatableMessage
    {
        return $this->message;
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
