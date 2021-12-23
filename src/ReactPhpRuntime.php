<?php

declare(strict_types=1);

namespace Drinksco\React;

use Psr\Http\Server\RequestHandlerInterface;
use React\EventLoop\Loop;
use React\Http\Message\ServerRequest;
use RingCentral\Psr7\Request;
use Symfony\Component\Runtime\GenericRuntime;
use Symfony\Component\Runtime\Resolver\ClosureResolver;
use Symfony\Component\Runtime\ResolverInterface;
use Symfony\Component\Runtime\RunnerInterface;
use Trowski\ReactFiber\FiberLoop;

/**
 * @psalm-type ReactPhpOptions array{port: int}
 */
final class ReactPhpRuntime extends GenericRuntime
{
    /** @var int */
    private mixed $port;

    /**
     * @param ReactPhpOptions $options
     */
    public function __construct(array $options)
    {
        $this->port = $options['port'];
        parent::__construct($options);
    }

    public function getRunner(?object $application): RunnerInterface
    {
        if ($application instanceof RequestHandlerInterface) {
            $loop = Loop::get();
            $serverFactory = new ReactHttpServerFactory();
            return new ReactPHPRunner(
                $loop,
                $serverFactory->create($application),
                sprintf('0.0.0.0:%s', $this->port)
            );
        }

        // if it's not a PSR-15 application, use the GenericRuntime to
        // run the application (see "Resolvable Applications" above)
        return parent::getRunner($application);
    }

    public function getResolver(callable $callable, \ReflectionFunction $reflector = null): ResolverInterface
    {
        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (!str_starts_with($key, 'HTTP_')) {
                continue;
            }
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $headers[$header] = $value;
        }

        return new ClosureResolver(
            \Closure::fromCallable($callable),
            \Closure::fromCallable(function () use ($headers) {
                return [[
                new ServerRequest(
                    $_SERVER['REQUEST_METHOD'] ?? 'GET',
                    $_SERVER['REQUEST_URI'] ?? '/',
                    $headers
                )
                ]];
            })
        );
    }
}
