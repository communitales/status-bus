<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2020 - 2026 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Test\Unit\Component\StatusBus;

use Communitales\Component\StatusBus\Failure\LogAndContinueDeliveryFailureHandler;
use Communitales\Component\StatusBus\Failure\RethrowDeliveryFailureHandler;
use Communitales\Component\StatusBus\Handler\InMemoryStatusHandler;
use Communitales\Component\StatusBus\Handler\StatusHandlerInterface;
use Communitales\Component\StatusBus\StatusBus;
use Communitales\Component\StatusBus\StatusMessage;
use Override;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Stringable;

#[CoversClass(StatusBus::class)]
#[CoversClass(InMemoryStatusHandler::class)]
#[CoversClass(LogAndContinueDeliveryFailureHandler::class)]
#[CoversClass(RethrowDeliveryFailureHandler::class)]
final class StatusBusTest extends TestCase
{
    public function testPublishesEveryMessageToEveryHandler(): void
    {
        $firstHandler = new InMemoryStatusHandler();
        $secondHandler = new InMemoryStatusHandler();
        $bus = new StatusBus([$firstHandler, $secondHandler]);
        $message = StatusMessage::info('Import started.');

        $bus->publish($message);
        $bus->publish($message);

        $this->assertSame([$message, $message], $firstHandler->messages());
        $this->assertSame([$message, $message], $secondHandler->messages());
    }

    public function testContinuesAfterHandlerFailure(): void
    {
        $exception = new RuntimeException('Broken handler');
        $brokenHandler = new readonly class ($exception) implements StatusHandlerInterface {
            public function __construct(private RuntimeException $exception)
            {
            }

            #[Override]
            public function handle(StatusMessage $message): void
            {
                throw $this->exception;
            }
        };
        $workingHandler = new InMemoryStatusHandler();
        $message = StatusMessage::warning('Partial import.');

        $logger = new class () extends AbstractLogger {
            /** @var list<array{level: mixed, message: string|Stringable, context: array<string, mixed>}> */
            public array $records = [];

            /** @param array<string, mixed> $context */
            #[Override]
            public function log(mixed $level, string|Stringable $message, array $context = []): void
            {
                $this->records[] = [
                    'context' => $context,
                    'level' => $level,
                    'message' => $message,
                ];
            }
        };

        $bus = new StatusBus(
            [$brokenHandler, $workingHandler],
            new LogAndContinueDeliveryFailureHandler($logger),
        );

        $bus->publish($message);

        $this->assertCount(1, $logger->records);
        $this->assertSame('error', $logger->records[0]['level']);
        $this->assertSame('Status message delivery failed.', $logger->records[0]['message']);
        $this->assertSame($exception, $logger->records[0]['context']['exception']);
        $this->assertSame($brokenHandler::class, $logger->records[0]['context']['handler']);
        $this->assertSame('warning', $logger->records[0]['context']['status_level']);
        $this->assertSame('Partial import.', $logger->records[0]['context']['status_message']);
        $this->assertSame([$message], $workingHandler->messages());
    }

    public function testRethrowsHandlerFailureWhenConfigured(): void
    {
        $brokenHandler = new class () implements StatusHandlerInterface {
            #[Override]
            public function handle(StatusMessage $message): void
            {
                throw new RuntimeException('Broken handler');
            }
        };
        $bus = new StatusBus([$brokenHandler], new RethrowDeliveryFailureHandler());

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessageIsOrContains('Broken handler');

        $bus->publish(StatusMessage::error('Import failed.'));
    }

    public function testIgnoresFailureWhileLoggingFailure(): void
    {
        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->once())
            ->method('error')
            ->willThrowException(new RuntimeException('Logger failed'));

        $handler = new class () implements StatusHandlerInterface {
            #[Override]
            public function handle(StatusMessage $message): void
            {
                throw new RuntimeException('Handler failed');
            }
        };

        $bus = new StatusBus([$handler], new LogAndContinueDeliveryFailureHandler($logger));
        $bus->publish(StatusMessage::error('Import failed.'));

        $this->addToAssertionCount(1);
    }
}
