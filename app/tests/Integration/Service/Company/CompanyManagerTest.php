<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Company;

use App\Service\Company\CompanyManager;
use App\Tests\Fixtures\Doctrine\UserCompanyFixture;
use App\Tests\Fixtures\Doctrine\UserFixture;
use App\Tests\Shared\AbstractTestCase;

class CompanyManagerTest extends AbstractTestCase
{
    private ?CompanyManager $companyManager;

    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            UserCompanyFixture::class,
        ]);

        $this->companyManager = $this->getService(CompanyManager::class);
    }

    public function testFindByUser(): void
    {
        $admin = $this->getReference(UserFixture::SUPER_ADMIN_USER);
        $user = $this->getReference(UserFixture::SIMPLE_USER);

        $this->assertCount(2, $this->companyManager->findByUser($admin));
        $this->assertCount(1, $this->companyManager->findByUser($user));

        $this->assertCount(5, $this->companyManager->findByUser($user, true));
    }
}
