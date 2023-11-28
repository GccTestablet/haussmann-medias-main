<?php

declare(strict_types=1);

namespace App\Tests\Unit\Twig\Runtime;

use App\Tests\Shared\AbstractTestCase;
use App\Tools\Parser\ArrayParser;
use App\Twig\Runtime\CoreExtensionRuntime;

class CoreExtensionRuntimeTest extends AbstractTestCase
{
    private ?CoreExtensionRuntime $coreExtensionRuntime;

    protected function setUp(): void
    {
        $this->coreExtensionRuntime = new CoreExtensionRuntime();
    }

    public function testCallStatic(): void
    {
        $this->assertSame(
            1,
            $this->coreExtensionRuntime->callStatic(ArrayParser::class, 'getFirstValue', [1, 2, 3])
        );
    }

    public function testExecuteClosureThrowClassDoesNotExist(): void
    {
        $this->expectExceptionMessage('Class "foo" does not exists');

        $this->coreExtensionRuntime->callStatic('foo', 'foo');
    }

    public function testExecuteClosureThrowMethodDoesNotExist(): void
    {
        $this->expectExceptionMessage('Method "foo" does not exists in class "App\Tools\Parser\ArrayParser"');

        $this->coreExtensionRuntime->callStatic(ArrayParser::class, 'foo');
    }

    public function testExecuteClosure(): void
    {
        $this->assertSame(
            3,
            $this->coreExtensionRuntime->executeClosure(fn (int $a, int $b) => $a + $b, [1, 2])
        );
    }

    public function testIsInstanceOf(): void
    {
        $this->assertTrue($this->coreExtensionRuntime->isInstanceOf(new \stdClass(), \stdClass::class));
    }
}
