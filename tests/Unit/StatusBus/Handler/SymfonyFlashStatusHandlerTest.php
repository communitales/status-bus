<?php

declare(strict_types=1);

/*
 * SPDX-FileCopyrightText: 2020 Communitales GmbH
 *
 * SPDX-License-Identifier: MIT
 */

namespace Communitales\Test\Unit\Component\StatusBus\Handler;

use Communitales\Component\StatusBus\Handler\SymfonyFlashStatusHandler;
use Communitales\Component\StatusBus\StatusMessage;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Translation\TranslatableMessage;

#[CoversClass(SymfonyFlashStatusHandler::class)]
#[UsesClass(StatusMessage::class)]
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
        $handler->handle(StatusMessage::info('Information'));
        $handler->handle(StatusMessage::success('Success'));

        $messages = $session->getFlashBag()->peek('danger');
        $this->assertCount(1, $messages);
        $this->assertInstanceOf(TranslatableMessage::class, $messages[0]);
        $this->assertSame('order.failed', $messages[0]->getMessage());
        $this->assertSame(['%order%' => 'A-42'], $messages[0]->getParameters());
        $this->assertSame('status', $messages[0]->getDomain());
        $this->assertCount(1, $session->getFlashBag()->peek('info'));
        $this->assertCount(1, $session->getFlashBag()->peek('success'));
    }

    #[DoesNotPerformAssertions]
    public function testDoesNothingWithoutRequestOrSession(): void
    {
        $requestStack = new RequestStack();
        $handler = new SymfonyFlashStatusHandler($requestStack);

        $handler->handle(StatusMessage::info('No request'));

        $requestStack->push(new Request());
        $handler->handle(StatusMessage::info('No session'));

        $request = new Request();
        $request->setSession($this->createStub(SessionInterface::class));

        $requestStack->push($request);
        $handler->handle(StatusMessage::info('No flash bag'));
    }
}
