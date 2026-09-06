<?php

declare(strict_types=1);

/*
 * SPDX-FileCopyrightText: 2020 Communitales GmbH
 *
 * SPDX-License-Identifier: MIT
 */

namespace Communitales\Test\Unit\Component\StatusBus\Handler;

use Communitales\Component\StatusBus\Handler\PsrLogStatusHandler;
use Communitales\Component\StatusBus\StatusMessage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Contracts\Translation\TranslatorInterface;

#[CoversClass(PsrLogStatusHandler::class)]
#[UsesClass(StatusMessage::class)]
final class PsrLogStatusHandlerTest extends TestCase
{
    public function testLogsTranslatedMessageAndStructuredContext(): void
    {
        $translator = $this->createMock(TranslatorInterface::class);
        $translator
            ->expects($this->once())
            ->method('trans')
            ->with('order.created', ['%order%' => 'A-42'], 'status', 'de')
            ->willReturn('Auftrag A-42 wurde erstellt.');

        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->once())
            ->method('log')
            ->with(LogLevel::NOTICE, 'Auftrag A-42 wurde erstellt.', [
                'order_id' => 42,
                'status_message' => [
                    'domain' => 'status',
                    'level' => 'success',
                    'message' => 'order.created',
                    'parameters' => ['%order%' => 'A-42'],
                ],
            ]);

        $handler = new PsrLogStatusHandler($logger, $translator, 'de');
        $handler->handle(StatusMessage::success(
            'order.created',
            ['%order%' => 'A-42'],
            'status',
            ['order_id' => 42],
        ));
    }

    public function testMapsLevelsWithoutTranslator(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $matcher = $this->exactly(3);
        $logger
            ->expects($matcher)
            ->method('log')
            ->willReturnCallback(static function (string $level, string $message) use ($matcher): void {
                $expected = match ($matcher->numberOfInvocations()) {
                    1 => [LogLevel::ERROR, 'Error'],
                    2 => [LogLevel::INFO, 'Info'],
                    3 => [LogLevel::WARNING, 'Warning'],
                };

                self::assertSame($expected, [$level, $message]);
            });

        $handler = new PsrLogStatusHandler($logger);
        $handler->handle(StatusMessage::error('Error'));
        $handler->handle(StatusMessage::info('Info'));
        $handler->handle(StatusMessage::warning('Warning'));
    }
}
