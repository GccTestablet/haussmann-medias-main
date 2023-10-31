<?php

declare(strict_types=1);

namespace App\Enum\Work;

use Symfony\Component\Intl\Countries;

enum OriginWorkEnum: string
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

    /**
     * @return string[]
     */
    public function getCountries(): array
    {
        $europeanCountries = ['FR', 'BE', 'DE', 'ES', 'FR', 'IT', 'LU', 'NL', 'PT'];

        return match ($this) {
            self::FRANCE => ['FR'],
            self::EUROPEAN => $europeanCountries,
            self::INTERNATIONAL => \array_diff(Countries::getCountryCodes(), $europeanCountries),
        };
    }
}
