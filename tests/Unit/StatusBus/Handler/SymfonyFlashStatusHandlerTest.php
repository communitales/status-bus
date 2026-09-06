<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2020 - 2026 Communitales GmbH (https://www.communitales.com/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Communitales\Test\Unit\Component\StatusBus\Handler;

use Communitales\Component\StatusBus\Handler\SymfonyFlashStatusHandler;
use Communitales\Component\StatusBus\StatusMessage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Translation\TranslatableMessage;

#[CoversClass(SymfonyFlashStatusHandler::class)]
final class SymfonyFlashStatusHandlerTest extends TestCase
{
    public function testStoresUntranslatedMessageInSession(): void
    {
        $session = new Session(new MockArraySessionStorage());
        $request = new Request();
        $request->setSession($session);

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $handler = new SymfonyFlashStatusHandler($requestStack);

        $handler->handle(StatusMessage::error(
            'order.failed',
            ['%order%' => 'A-42'],
            'status',
        ));

        $messages = $session->getFlashBag()->peek('danger');
        $this->assertCount(1, $messages);
        $this->assertInstanceOf(TranslatableMessage::class, $messages[0]);
        $this->assertSame('order.failed', $messages[0]->getMessage());
        $this->assertSame(['%order%' => 'A-42'], $messages[0]->getParameters());
        $this->assertSame('status', $messages[0]->getDomain());
    }

    #[DoesNotPerformAssertions]
    public function testDoesNothingWithoutRequestOrSession(): void
    {
        $requestStack = new RequestStack();
        $handler = new SymfonyFlashStatusHandler($requestStack);

        $handler->handle(StatusMessage::info('No request'));

        $requestStack->push(new Request());
        $handler->handle(StatusMessage::info('No session'));
    }
}
