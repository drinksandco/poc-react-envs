<?php

declare(strict_types=1);

namespace Drinksco\Test\React;

use Drinksco\React\ReactPHPRunner;
use Drinksco\React\ReactPhpRuntime;
use PHPUnit\Framework\TestCase;
use Psr\Http\Server\RequestHandlerInterface;
use Symfony\Component\Runtime\RuntimeInterface;

final class ReactPHPRuntimeTest extends TestCase
{
    public function testOverridesGetRunnerMethodFromGenericRuntime(): void
    {
        $handler = $this->createMock(RequestHandlerInterface::class);
        $runtime = new ReactPhpRuntime([
            'port' => 3000,
        ]);

        $runner = $runtime->getRunner($handler);

        self::assertInstanceOf(RuntimeInterface::class, $runtime);
        self::assertInstanceOf(ReactPHPRunner::class, $runner);
    }
}
    