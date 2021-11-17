<?php

declare(strict_types=1);

namespace Drinksco\React;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use React\Http\HttpServer;

final class ReactHttpServerFactory
{
    public function create(RequestHandlerInterface $handler): HttpServer
    {
        return new HttpServer(
            new \React\Http\Middleware\StreamingRequestMiddleware(),
            new \React\Http\Middleware\LimitConcurrentRequestsMiddleware(100), // 100 concurrent buffering handlers
            new \React\Http\Middleware\RequestBodyBufferMiddleware(2 * 1024 * 1024), // 2 MiB per request
            new \React\Http\Middleware\RequestBodyParserMiddleware(),
            function (ServerRequestInterface $request) use ($handler): ResponseInterface {
                return $handler->handle($request);
            }
        );
    }
}
