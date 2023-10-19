<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\User;

use App\Entity\Company;
use App\Entity\User;
use App\Service\User\UserUpdater;
use App\Tests\AbstractTestCase;
use App\Tests\Fixtures\Doctrine\CompanyFixture;
use App\Tests\Fixtures\Doctrine\UserFixture;

class UserUpdaterTest extends AbstractTestCase
{
    private ?UserUpdater $userUpdater;

    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            UserFixture::class,
            CompanyFixture::class,
        ]);
        $this->userUpdater = $this->getService(UserUpdater::class);
    }

    public function testUpdateConnectedOn(): void
    {
        /** @var User $user */
        $user = $this->getReference(UserFixture::SUPER_ADMIN_USER);

        /** @var Company $company */
        $company = $this->getReference(CompanyFixture::HAUSSMANN_MEDIAS);

        $this->assertNull($user->getConnectedOn());

        $this->userUpdater->updateConnectedOn($user, $company);

        $this->assertSame($company, $user->getConnectedOn());
    }
}
