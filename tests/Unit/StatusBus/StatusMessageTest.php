<?php

/**
 * @copyright   Copyright (c) 2024 Communitales GmbH (https://www.communitales.com/)
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
class StatusMessageTest extends TestCase
{
    public function testCreateSuccessMessageTranslatable(): void
    {
        $status = StatusMessage::createSuccessMessage(
            new TranslatableMessage('status.success')
        );

        $this->assertEquals('success', $status->getType());
        $message = $status->getMessage();
        if ($message instanceof TranslatableMessage) {
            $this->assertEquals('status.success', $message->getMessage());
        } else {
            self::fail('Expected instance of TranslatableMessage');
        }
    }

    public function testCreateSuccessMessageString(): void
    {
        $status = StatusMessage::createSuccessMessage(
            'status.success'
        );

        $this->assertEquals('success', $status->getType());
        $message = $status->getMessage();
        $this->assertEquals('status.success', $message);
    }

    public function testToString(): void
    {
        $status = StatusMessage::createSuccessMessage(
            new TranslatableMessage('status.success')
        );
        self::assertEquals('status.success', (string)$status);

        $status = StatusMessage::createSuccessMessage(
            'status.success'
        );
        self::assertEquals('status.success', (string)$status);
    }
}
