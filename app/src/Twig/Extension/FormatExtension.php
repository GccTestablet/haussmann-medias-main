<?php

declare(strict_types=1);

namespace App\Twig\Extension;

use App\Twig\Runtime\FormatExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class FormatExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('date_format', [FormatExtensionRuntime::class, 'formatDate'], [
                'is_safe' => ['html'],
            ]),
            new TwigFilter('datetime_format', [FormatExtensionRuntime::class, 'formatDateTime'], [
                'is_safe' => ['html'],
            ]),
            new TwigFilter('initial_format', [FormatExtensionRuntime::class, 'formatInitial']),
            new TwigFilter('country_format', [FormatExtensionRuntime::class, 'formatCountry']),
            new TwigFilter('user_format', [FormatExtensionRuntime::class, 'formatUser'], [
                'is_safe' => ['html'],
            ]),
            new TwigFilter('attributes_format', [FormatExtensionRuntime::class, 'formatAttributes'], [
                'is_safe' => ['html'],
            ]),
            new TwigFilter('bool_format', [FormatExtensionRuntime::class, 'boolFormat'], [
                'is_safe' => ['html'],
            ]),
            new TwigFilter('archive_format', [FormatExtensionRuntime::class, 'archivedFormat'], [
                'is_safe' => ['html'],
            ]),
        ];
    }
}
