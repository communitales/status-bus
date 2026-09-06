<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2020 - 2026 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Test\Unit\Component\StatusBus;

use Communitales\Component\StatusBus\StatusLevel;
use Communitales\Component\StatusBus\StatusMessage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatorInterface;

#[CoversClass(StatusMessage::class)]
final class StatusMessageTest extends TestCase
{
    public function testCreatesMessagesForEveryLevel(): void
    {
        $this->assertSame(StatusLevel::Error, StatusMessage::error('Error')->getLevel());
        $this->assertSame(StatusLevel::Info, StatusMessage::info('Info')->getLevel());
        $this->assertSame(StatusLevel::Success, StatusMessage::success('Success')->getLevel());
        $this->assertSame(StatusLevel::Warning, StatusMessage::warning('Warning')->getLevel());
    }

    public function testExposesTranslationAndLogData(): void
    {
        $message = StatusMessage::success(
            'order.created',
            ['%order%' => 'A-42'],
            'status',
            ['order_id' => 42],
        );

        $this->assertSame('order.created', $message->getMessage());
        $this->assertSame('order.created', (string)$message);
        $this->assertSame(['%order%' => 'A-42'], $message->getParameters());
        $this->assertSame('status', $message->getDomain());
        $this->assertSame(['order_id' => 42], $message->getContext());

        $translatable = $message->toTranslatableMessage();
        $this->assertInstanceOf(TranslatableMessage::class, $translatable);
        $this->assertSame('order.created', $translatable->getMessage());
        $this->assertSame(['%order%' => 'A-42'], $translatable->getParameters());
        $this->assertSame('status', $translatable->getDomain());
    }

    public function testTranslatesMessage(): void
    {
        $translator = $this->createMock(TranslatorInterface::class);
        $translator
            ->expects($this->once())
            ->method('trans')
            ->with('order.created', ['%order%' => 'A-42'], 'status', 'de')
            ->willReturn('Auftrag A-42 wurde erstellt.');

        $message = StatusMessage::success(
            'order.created',
            ['%order%' => 'A-42'],
            'status',
        );

        $this->assertSame('Auftrag A-42 wurde erstellt.', $message->trans($translator, 'de'));
    }
}
