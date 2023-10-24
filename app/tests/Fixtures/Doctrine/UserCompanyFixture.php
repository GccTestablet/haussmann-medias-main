<?php

declare(strict_types=1);

namespace App\Tests\Fixtures\Doctrine;

use App\Entity\UserCompany;
use App\Enum\User\UserCompanyPermissionEnum;
use App\Tests\Fixtures\Doctrine\Shared\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserCompanyFixture extends AbstractFixture implements DependentFixtureInterface
{
    private const ROWS = [
        [
            'user' => UserFixture::SUPER_ADMIN_USER,
            'company' => CompanyFixture::HAUSSMANN_MEDIAS,
            'permission' => UserCompanyPermissionEnum::ADMIN,
        ],
        [
            'user' => UserFixture::SUPER_ADMIN_USER,
            'company' => CompanyFixture::CHROME_FILMS,
            'permission' => UserCompanyPermissionEnum::ADMIN,
        ],
        [
            'user' => UserFixture::SIMPLE_USER,
            'company' => CompanyFixture::HAUSSMANN_MEDIAS,
            'permission' => UserCompanyPermissionEnum::ADMIN,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::ROWS as $row) {
            $this->denormalizeReferenceFields($row, [
                'user',
                'company',
            ]);

            $userCompany = new UserCompany($row['user'], $row['company']);
            $this->merge($row, $userCompany, [
                'user',
                'company',
            ]);

            $manager->persist($userCompany);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixture::class,
            CompanyFixture::class,
        ];
    }
}
