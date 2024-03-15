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
    public function testCreateSuccessMessage()
    {
        $status = StatusMessage::createSuccessMessage(
            new TranslatableMessage('status.success')
        );

        $this->assertEquals('success', $status->getType());
        $this->assertEquals('status.success', $status->getMessage()->getMessage());
    }
}
