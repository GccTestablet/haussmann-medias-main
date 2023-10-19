<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\User;

use App\Entity\User;
use App\Service\User\UserManager;
use App\Tests\AbstractTestCase;
use App\Tests\Fixtures\Doctrine\UserFixture;

class UserManagerTest extends AbstractTestCase
{
    private ?UserManager $userManager;

    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            UserFixture::class,
        ]);

        $this->userManager = $this->getService(UserManager::class);
    }

    public function testFindAll(): void
    {
        $this->assertCount(2, $this->userManager->findAll());
    }

    public function testFindByEmail(): void
    {
        $this->assertInstanceOf(User::class, $this->userManager->findByEmail('super-admin@hm.mail'));
        $this->assertNull($this->userManager->findByEmail('no-existing@hm.mail'));
    }
}
