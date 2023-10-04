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
            new TwigFilter('date_format', [FormatExtensionRuntime::class, 'formatDate']),
            new TwigFilter('datetime_format', [FormatExtensionRuntime::class, 'formatDateTime']),
        ];
    }
}
