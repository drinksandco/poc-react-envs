<?php

declare(strict_types=1);

use Drinksco\React\ReactHttpServerFactory;
use Drinksco\React\StreamingRequestFiberMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use React\EventLoop\Loop;
use React\Http\HttpServer;
use React\Http\Message\Response;
use React\Socket\SocketServer;

require_once 'vendor/autoload.php';

$foo = new class implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200, [], 'Hello World!!!');
    }
};

$eventLoop = Loop::get();

$server = new HttpServer(
    $eventLoop,
    new \React\Http\Middleware\StreamingRequestMiddleware(),
    new \React\Http\Middleware\LimitConcurrentRequestsMiddleware(100), // 100 concurrent buffering handlers
    new \React\Http\Middleware\RequestBodyBufferMiddleware(2 * 1024), // 2 MiB per request
    new \React\Http\Middleware\RequestBodyParserMiddleware(),
    function (ServerRequestInterface $request): ResponseInterface {
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            '[{"title":"Barcelona","location_type":"City","woeid":753692,"latt_long":"41.385578,2.168740"}]'
        );
    }
);

$socket = new SocketServer('0.0.0.0:8080', [], $eventLoop);
$server->listen($socket);

$eventLoop->run();

