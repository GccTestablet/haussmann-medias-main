<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum;

use App\Tests\Shared\AbstractTestCase;

abstract class AbstractEnumTestCase extends AbstractTestCase
{
    protected static string $enum;

    /**
     * @return array<\BackedEnum>
     */
    protected function getCases(): array
    {
        return \forward_static_call_array([static::$enum, 'cases'], []);
    }

    /**
     * @param array<mixed> $expected
     */
    public function assertAsCallback(array $expected, callable $callback): void
    {
        $this->assertSame($expected, \array_map($callback, $this->getCases()));
    }
}
