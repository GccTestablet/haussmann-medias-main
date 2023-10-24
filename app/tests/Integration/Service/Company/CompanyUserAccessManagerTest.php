<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Company;

use App\Entity\User;
use App\Enum\User\UserCompanyPermissionEnum;
use App\Service\Company\CompanyUserAccessManager;
use App\Tests\Fixtures\Doctrine\CompanyFixture;
use App\Tests\Fixtures\Doctrine\UserCompanyFixture;
use App\Tests\Fixtures\Doctrine\UserFixture;
use App\Tests\Shared\AbstractTestCase;

class CompanyUserAccessManagerTest extends AbstractTestCase
{
    private ?CompanyUserAccessManager $companyUserAccessManager;

    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            UserCompanyFixture::class,
        ]);

        $this->companyUserAccessManager = $this->getService(CompanyUserAccessManager::class);
    }

    public function testGetPermission(): void
    {
        /** @var User $user */
        $user = $this->getReference(UserFixture::SUPER_ADMIN_USER);

        $this->assertSame(UserCompanyPermissionEnum::ADMIN, $this->companyUserAccessManager->getPermission(
            $this->getReference(CompanyFixture::HAUSSMANN_MEDIAS),
            $user,
        ));

        $this->assertNull($this->companyUserAccessManager->getPermission(
            $this->getReference(CompanyFixture::MY_DIGITAL_COMPANY),
            $user,
        ));
    }

    public function testHasPermission(): void
    {
        /** @var User $user */
        $user = $this->getReference(UserFixture::SUPER_ADMIN_USER);

        $this->assertTrue($this->companyUserAccessManager->hasPermission(
            $this->getReference(CompanyFixture::HAUSSMANN_MEDIAS),
            $user,
            UserCompanyPermissionEnum::ADMIN,
        ));

        $this->assertFalse($this->companyUserAccessManager->hasPermission(
            $this->getReference(CompanyFixture::MY_DIGITAL_COMPANY),
            $user,
            UserCompanyPermissionEnum::ADMIN,
        ));
    }
}
