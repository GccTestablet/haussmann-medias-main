<?php

declare(strict_types=1);

namespace App\Tests\Unit\Twig\Extension;

use App\Tests\Shared\AbstractTestCase;
use App\Twig\Extension\FormatExtension;
use App\Twig\Runtime\FormatExtensionRuntime;

class FormatExtensionTest extends AbstractTestCase
{
    private ?FormatExtension $formatExtension;

    protected function setUp(): void
    {
        $this->formatExtension = new FormatExtension();
    }

    public function testGetFilters(): void
    {
        $actual = \array_map(
            static fn ($filter) => [$filter->getName(), $filter->getCallable()],
            $this->formatExtension->getFilters()
        );

        $this->assertSame([
            ['date_format', [FormatExtensionRuntime::class, 'formatDate']],
            ['datetime_format', [FormatExtensionRuntime::class, 'formatDateTime']],
            ['initial_format', [FormatExtensionRuntime::class, 'formatInitial']],
            ['country_format', [FormatExtensionRuntime::class, 'formatCountry']],
            ['user_format', [FormatExtensionRuntime::class, 'formatUser']],
            ['attributes_format', [FormatExtensionRuntime::class, 'formatAttributes']],
            ['bool_format', [FormatExtensionRuntime::class, 'boolFormat']],
            ['archive_format', [FormatExtensionRuntime::class, 'archivedFormat']],
        ], $actual);
    }
}
