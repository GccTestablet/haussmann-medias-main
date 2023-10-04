<?php

declare(strict_types=1);

namespace App\Twig\Runtime;

use App\Tools\Parser\DateParser;
use Twig\Extension\RuntimeExtensionInterface;

class FormatExtensionRuntime implements RuntimeExtensionInterface
{
    public function formatDate(?\DateTime $dateTime): ?string
    {
        return $dateTime?->format(DateParser::FR_DATE_FORMAT);
    }

    public function formatDateTime(?\DateTime $dateTime): ?string
    {
        return $dateTime?->format(DateParser::FR_DATETIME_FORMAT);
    }
}
