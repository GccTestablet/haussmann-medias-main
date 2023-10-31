<?php

declare(strict_types=1);

namespace App\Enum\Work;

enum WorkQuotaEnum: string
{
    case FRANCE = 'france';
    case EUROPEAN = 'european';
    case INTERNATIONAL = 'international';

    public function getAsText(): string
    {
        return match ($this) {
            self::FRANCE => 'France',
            self::EUROPEAN => 'European',
            self::INTERNATIONAL => 'International',
        };
    }
}
