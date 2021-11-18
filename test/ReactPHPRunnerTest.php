<?php

declare(strict_types=1);

namespace Drinksco\Test\React;

use Drinksco\React\ReactPHPRunner;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use React\EventLoop\LoopInterface;
use React\Http\HttpServer;
use Symfony\Component\Runtime\RunnerInterface;

final class ReactPHPRunnerTest extends TestCase
{
    private const PORT = '0.0.0.0:3000';
    private ReactPHPRunner $runner;
    /** @var MockObject|LoopInterface */
    private $eventLoop;
    /** @var mixed|MockObject|HttpServer */
    private mixed $server;

    protected function setUp(): void
    {
        $requestHandler = $this->createMock(RequestHandlerInterface::class);
        $this->eventLoop = $this->createMock(LoopInterface::class);
        $this->server = new HttpServer(
            $this->eventLoop,
            static fn(ServerRequestInterface $request) => $requestHandler->handle($request)
        );
        $port = self::PORT;
        $this->runner = new ReactPHPRunner(
            $this->eventLoop,
            $this->server,
            $port
        );
    }

    public function testItIsCreatedWithARequestAndPortNumber(): void
    {
        self::assertInstanceOf(RunnerInterface::class, $this->runner);
    }

    public function testItRunsReactPHPApplication(): void
    {
        $this->eventLoop->expects(self::once())
            ->method('run');
        $this->runner->run();
    }
}
    