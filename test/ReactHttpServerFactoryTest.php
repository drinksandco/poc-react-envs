<?php

declare(strict_types=1);

namespace Drinksco\Test\React;

use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use React\Http\HttpServer;

final class ReactHttpServerFactoryTest extends TestCase
{
    public function testItCreatesInstancesOfReactHttpServer(): void
    {
        $requestHandler = $this->createMock(RequestHandlerInterface::class);
        $factory = new \Drinksco\React\ReactHttpServerFactory();
        $server = $factory->create($requestHandler);
        self::assertInstanceOf(HttpServer::class, $server);
    }
}
    