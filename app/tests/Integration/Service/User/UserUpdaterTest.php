<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\User;

use App\Entity\Company;
use App\Entity\User;
use App\Service\User\UserUpdater;
use App\Tests\Fixtures\Doctrine\CompanyFixture;
use App\Tests\Fixtures\Doctrine\UserFixture;
use App\Tests\Shared\AbstractTestCase;

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

        /** @var Company $company1 */
        $company1 = $this->getReference(CompanyFixture::HAUSSMANN_MEDIAS);

        /** @var Company $company2 */
        $company2 = $this->getReference(CompanyFixture::CHROME_FILMS);

        $this->assertSame($company1, $user->getConnectedOn());

        $this->userUpdater->updateConnectedOn($user, $company2);

        $this->assertSame($company2, $user->getConnectedOn());
    }
}
