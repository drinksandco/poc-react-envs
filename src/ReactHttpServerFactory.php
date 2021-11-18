<?php

declare(strict_types=1);

namespace Drinksco\React;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use React\Http\Browser;
use React\Http\HttpServer;
use Trowski\ReactFiber\FiberLoop;

final class ReactHttpServerFactory
{
    public function __construct(
        private FiberLoop $loop
    ) {
    }

    public function create(RequestHandlerInterface $handler): HttpServer
    {
        $browser = new Browser($this->loop);
        $client = new \Drinksco\React\FiberHttpClient(
            $this->loop,
            $browser
        );

        return new HttpServer(
            $this->loop,
            new StreamingRequestFiberMiddleware($this->loop),
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
