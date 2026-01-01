<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2020 - 2026 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Test\Unit\Component\StatusBus\StatusBus;

use Communitales\Component\StatusBus\StatusMessage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\TranslatableMessage;

/**
 * Class StatusMessageTest
 */
final class StatusMessageTest extends TestCase
{
    public function testCreateSuccessMessageTranslatable(): void
    {
        $status = StatusMessage::createSuccessMessage(
            new TranslatableMessage('status.success')
        );

        $this->assertSame('success', $status->getType());
        $message = $status->getMessage();
        if ($message instanceof TranslatableMessage) {
            $this->assertSame('status.success', $message->getMessage());
        } else {
            self::fail('Expected instance of TranslatableMessage');
        }
    }

    public function testCreateSuccessMessageString(): void
    {
        $status = StatusMessage::createSuccessMessage(
            'status.success'
        );

        $this->assertSame('success', $status->getType());
        $message = $status->getMessage();
        $this->assertSame('status.success', $message);
    }

    public function testToString(): void
    {
        $status = StatusMessage::createSuccessMessage(
            new TranslatableMessage('status.success')
        );
        $this->assertSame('status.success', (string)$status);

        $status = StatusMessage::createSuccessMessage(
            'status.success'
        );
        $this->assertSame('status.success', (string)$status);
    }
}
