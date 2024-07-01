<?php

/**
 * @copyright   Copyright (c) 2024 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Test\Unit\Component\StatusBus\StatusBus;

use ArrayObject;
use Communitales\Component\StatusBus\Handler\ArrayStatusBusHandler;
use Communitales\Component\StatusBus\StatusBus;
use Communitales\Component\StatusBus\StatusBusInterface;
use Communitales\Component\StatusBus\StatusMessage;
use Override;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\TranslatableMessage;

/**
 * Class StatusBusTest
 */
class StatusBusTest extends TestCase
{
    /**
     * @var ArrayObject<array-key, StatusMessage>
     */
    private ArrayObject $messagesList;

    private StatusBus $statusBus;

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

        $this->assertEquals(StatusBusInterface::STATUS_ERROR, $this->statusBus->getStatus());
        $this->assertEquals(1, $this->messagesList->count());

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

        $this->assertEquals(StatusBusInterface::STATUS_ERROR, $this->statusBus->getStatus());
        $this->assertEquals(1, $this->messagesList->count());

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
        /** @var ArrayObject<array-key, StatusMessage> */
        return new ArrayObject();
    }
}
