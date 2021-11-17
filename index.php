<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use React\Http\Message\Response;

$_SERVER['APP_RUNTIME'] = \Drinksco\React\ReactPhpRuntime::class;
$_SERVER['APP_DEBUG'] = 0;
$_SERVER['APP_RUNTIME_OPTIONS'] = [
    'port' => 5555,
];

require_once 'vendor/autoload_runtime.php';

$foo = new class implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(200, [], 'Hello World!!!');
    }
};

return function (array $context) use ($foo) {

    return $foo;
};
