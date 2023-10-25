<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\User;

use App\Entity\User;
use App\Service\User\UserActivityManager;
use App\Tests\Fixtures\Doctrine\UserFixture;
use App\Tests\Shared\AbstractTestCase;

class UserActivityManagerTest extends AbstractTestCase
{
    private ?UserActivityManager $userActivityManager;

    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            UserFixture::class,
        ]);

        $this->userActivityManager = $this->getService(UserActivityManager::class);
    }

    public function testUpdateLastLogin(): void
    {
        /** @var User $user */
        $user = $this->getReference(UserFixture::SUPER_ADMIN_USER);

        $this->assertNull($user->getLastLogin());

        $this->userActivityManager->updateLastLogin($user);

        $this->assertInstanceOf(\DateTime::class, $user->getLastLogin());
    }

    public function testUpdateLastActivity(): void
    {
        /** @var User $user */
        $user = $this->getReference(UserFixture::SUPER_ADMIN_USER);

        $this->assertNull($user->getLastActivity());

        $this->userActivityManager->updateLastActivity($user);

        $this->assertInstanceOf(\DateTime::class, $user->getLastActivity());
    }
}
