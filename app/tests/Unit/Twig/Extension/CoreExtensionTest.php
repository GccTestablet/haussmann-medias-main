<?php

declare(strict_types=1);

namespace App\Tests\Unit\Twig\Extension;

use App\Tests\Shared\AbstractTestCase;
use App\Twig\Extension\CoreExtension;
use App\Twig\Runtime\CoreExtensionRuntime;

class CoreExtensionTest extends AbstractTestCase
{
    private ?CoreExtension $coreExtension;

    protected function setUp(): void
    {
        $this->coreExtension = new CoreExtension();
    }

    public function testGetFunctions(): void
    {
        $actual = \array_map(
            static fn ($function) => [$function->getName(), $function->getCallable()],
            $this->coreExtension->getFunctions()
        );

        $this->assertSame([
            ['call_static', [CoreExtensionRuntime::class, 'callStatic']],
            ['execute', [CoreExtensionRuntime::class, 'executeClosure']],
        ], $actual);
    }

    public function testGetTests(): void
    {
        $actual = \array_map(
            static fn ($test) => [$test->getName(), $test->getCallable()],
            $this->coreExtension->getTests()
        );

        $this->assertSame([
            ['instanceof', [CoreExtensionRuntime::class, 'isInstanceOf']],
        ], $actual);
    }
}
