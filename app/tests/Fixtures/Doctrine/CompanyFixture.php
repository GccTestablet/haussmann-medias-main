<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine;

use App\Entity\Company;
use App\Tests\Fixtures\Doctrine\Shared\AbstractFixture;
use Doctrine\Persistence\ObjectManager;

class CompanyFixture extends AbstractFixture
{
    final public const HAUSSMANN_MEDIAS = 'company.haussmann_medias';
    final public const CHROME_FILMS = 'company.chrome_films';
    final public const HKA_FILMS = 'company.hka_films';
    final public const MEDIAWAN = 'company.mediawan';
    final public const MY_DIGITAL_COMPANY = 'company.my_digital_company';

    private const ROWS = [
        self::HAUSSMANN_MEDIAS => [
            'name' => 'Haussmann Medias',
        ],
        self::CHROME_FILMS => [
            'name' => 'Chrome Films',
        ],
        self::HKA_FILMS => [
            'name' => 'HKA Films',
        ],
        self::MEDIAWAN => [
            'name' => 'Mediawan',
        ],
        self::MY_DIGITAL_COMPANY => [
            'name' => 'My Digital Company',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $reference => $row) {
            $company = new Company();
            $this->merge($row, $company);

            $manager->persist($company);
            $this->setReference($reference, $company);
        }

        $manager->flush();
    }
}
