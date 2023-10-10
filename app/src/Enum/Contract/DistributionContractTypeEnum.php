<?php

declare(strict_types=1);

namespace App\Enum\Contract;

enum DistributionContractTypeEnum: string
{
    case ONE_OFF = 'one_off';
    case RECURRING = 'recurring';

    public function getAsText(): string
    {
        return match ($this) {
            self::ONE_OFF => 'One-off',
            self::RECURRING => 'Recurring',
        };
    }
}
