<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2020 - 2026 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Test\Unit\Component\StatusBus\StatusBus;

use ArrayObject;
use Communitales\Component\StatusBus\Handler\ArrayStatusBusHandler;
use Communitales\Component\StatusBus\StatusBus;
use Communitales\Component\StatusBus\StatusBusAwareInterface;
use Communitales\Component\StatusBus\StatusBusAwareTrait;
use Communitales\Component\StatusBus\StatusBusInterface;
use Communitales\Component\StatusBus\StatusMessage;
use Override;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\TranslatableMessage;

/**
 * Class StatusBusTest
 */
final class StatusBusTest extends TestCase implements StatusBusAwareInterface
{
    use StatusBusAwareTrait;

    /**
     * @var ArrayObject<array-key, StatusMessage>
     */
    private ArrayObject $messagesList;

    #[Override]
    protected function setUp(): void
    {
        $this->messagesList = $this->createMessageList();
        $handler = new ArrayStatusBusHandler($this->messagesList);

        $this->statusBus = new StatusBus(new ArrayObject([$handler]));
    }

    public function testAddErrorTranslatable(): void
    {
        $message = new TranslatableMessage('status.error');
        $this->statusBus->addError($message);

        $this->assertSame(StatusBusInterface::STATUS_ERROR, $this->statusBus->getStatus());
        $this->assertCount(1, $this->messagesList);

        /** @var StatusMessage $statusMessage */
        $statusMessage = $this->messagesList->getIterator()->current();
        $this->assertEquals($message, $statusMessage->getMessage());
        $this->assertEquals(StatusMessage::TYPE_ERROR, $statusMessage->getType());
        $this->assertTrue($statusMessage->isShown());
    }

    public function testAddErrorString(): void
    {
        $message = 'status.error';
        $this->statusBus->addError($message);

        $this->assertSame(StatusBusInterface::STATUS_ERROR, $this->statusBus->getStatus());
        $this->assertCount(1, $this->messagesList);

        /** @var StatusMessage $statusMessage */
        $statusMessage = $this->messagesList->getIterator()->current();
        $this->assertEquals($message, $statusMessage->getMessage());
        $this->assertEquals(StatusMessage::TYPE_ERROR, $statusMessage->getType());
        $this->assertTrue($statusMessage->isShown());
    }

    /**
     * @return ArrayObject<array-key, StatusMessage>
     */
    private function createMessageList(): ArrayObject
    {
        return new ArrayObject();
    }
}
