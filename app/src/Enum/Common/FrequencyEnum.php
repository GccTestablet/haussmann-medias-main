<?php

declare(strict_types=1);

namespace App\Enum\Common;

enum FrequencyEnum: string
{
    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case MONTHLY = 'monthly';
    case QUARTERLY = 'quarterly';
    case HALF_YEARLY = 'half-yearly';
    case YEARLY = 'yearly';

    public function getAsText(): string
    {
        return match ($this) {
            self::DAILY => 'Daily',
            self::WEEKLY => 'Weekly',
            self::MONTHLY => 'Monthly',
            self::QUARTERLY => 'Quarterly',
            self::HALF_YEARLY => 'Half-yearly',
            self::YEARLY => 'Yearly',
        };
    }
}
