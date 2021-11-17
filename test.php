<?php

declare(strict_types=1);

use Drinksco\React\ReactHttpServerFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use React\EventLoop\Loop;
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

$serverFactory = new ReactHttpServerFactory();
$server = $serverFactory->create($foo);

$socket = new SocketServer('0.0.0.0:5555', [], $eventLoop);
$server->listen($socket);

$eventLoop->run();

