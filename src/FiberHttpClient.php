<?php

declare(strict_types=1);

namespace Drinksco\React;

use Psr\Http\Message\ResponseInterface;
use React\Http\Browser;
use React\Http\Io\EmptyBodyStream;
use React\Stream\ReadableStreamInterface;
use React\Stream\WritableResourceStream;
use RingCentral\Psr7\Response;
use RingCentral\Psr7\Stream;
use Trowski\ReactFiber\FiberLoop;

use function React\Promise\Stream\buffer;

final class FiberHttpClient
{
    public function __construct(
        private FiberLoop $loop,
        private Browser $browser
    ) {
    }

    public function request(string $method, string $url): ResponseInterface
    {
        /** @var Response $response */
        $response = $this->loop->await(
            $this->browser->request($method, $url)
        );

        return new Response(200, [], $response->getBody()->getContents());
    }
}
