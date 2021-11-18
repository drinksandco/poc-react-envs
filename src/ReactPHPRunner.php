<?php

declare(strict_types=1);

namespace Drinksco\React;

use React\EventLoop\LoopInterface;
use React\Http\HttpServer;
use React\Socket\SocketServer;
use Symfony\Component\Runtime\RunnerInterface;

final class ReactPHPRunner implements RunnerInterface
{
    public function __construct(
        private LoopInterface $eventLoop,
        private HttpServer $server,
        private string $fullHost
    ) {
    }

    public function run(): int
    {
        // start the ReactPHP server
        $socket = new SocketServer($this->fullHost, [], $this->eventLoop);
        $this->server->listen($socket);

        $this->eventLoop->run();

        return 0;
    }
}
