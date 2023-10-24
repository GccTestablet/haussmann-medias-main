<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Company;

use App\Entity\User;
use App\Entity\UserCompany;
use App\Service\Company\CompanyUserManager;
use App\Tests\Fixtures\Doctrine\CompanyFixture;
use App\Tests\Fixtures\Doctrine\UserCompanyFixture;
use App\Tests\Fixtures\Doctrine\UserFixture;
use App\Tests\Shared\AbstractTestCase;

class CompanyUserManagerTest extends AbstractTestCase
{
    private ?CompanyUserManager $companyUserManager;

    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            UserCompanyFixture::class,
        ]);

        $this->companyUserManager = $this->getService(CompanyUserManager::class);
    }

    public function testFindByCompanyAndUser(): void
    {
        /** @var User $user */
        $user = $this->getReference(UserFixture::SUPER_ADMIN_USER);

        $this->assertInstanceOf(
            UserCompany::class,
            $this->companyUserManager->findByCompanyAndUser(
                $this->getReference(CompanyFixture::HAUSSMANN_MEDIAS),
                $user,
            )
        );

        $this->assertNull(
            $this->companyUserManager->findByCompanyAndUser(
                $this->getReference(CompanyFixture::MY_DIGITAL_COMPANY),
                $user,
            )
        );
    }
}
