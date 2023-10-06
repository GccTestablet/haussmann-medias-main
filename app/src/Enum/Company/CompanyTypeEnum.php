<?php

declare(strict_types=1);

namespace App\Enum\Company;

enum CompanyTypeEnum: string
{
    case INTERNATIONAL_SELLER = 'international_seller';
    case LOCAL_SELLER = 'local_seller';
    case DISTRIBUTOR = 'distributor';

    public function getAsText(): string
    {
        return match ($this) {
            self::INTERNATIONAL_SELLER => 'International seller',
            self::LOCAL_SELLER => 'Local seller',
            self::DISTRIBUTOR => 'Distributor',
        };
    }
}
