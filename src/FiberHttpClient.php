<?php

declare(strict_types=1);

namespace Drinksco\React;

use Psr\Http\Message\ResponseInterface;
use React\Http\Browser;
use RingCentral\Psr7\Response;

use function React\Async\await;

final class FiberHttpClient
{
    public function __construct(
        private Browser $browser
    ) {
    }

    public function request(string $method, string $url): ResponseInterface
    {
        /** @var Response $response */
        $response = await(
            $this->browser->request($method, $url)
        );

        return new Response(200, [], $response->getBody()->getContents());
    }
}
