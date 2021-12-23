<?php

declare(strict_types=1);

namespace Drinksco\React;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use React\EventLoop\Loop;
use React\Http\Browser;
use React\Http\HttpServer;

final class ReactHttpServerFactory
{
    public function create(RequestHandlerInterface $handler): HttpServer
    {
        $loop = Loop::get();
        $browser = new Browser($loop);
        $client = new FiberHttpClient($browser);

        return new HttpServer(
            $loop,
            new StreamingRequestFiberMiddleware(),
            new \React\Http\Middleware\RequestBodyBufferMiddleware(1024), // 2 MiB per request
            new \React\Http\Middleware\RequestBodyParserMiddleware(),
            function (ServerRequestInterface $request) use ($client, $handler): ResponseInterface {
                $response = $client->request('GET', 'http://test_external_client:8080/');
                $request = $request->withAttribute('weather', $response->getBody()->getContents());

                return $handler->handle($request);
            }
        );
    }
}
