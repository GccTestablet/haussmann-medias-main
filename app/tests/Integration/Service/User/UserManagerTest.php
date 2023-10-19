<?php

namespace App\Tests\Integration\Service\User;

use App\Entity\User;
use App\Service\User\UserManager;
use App\Tests\AbstractTestCase;

class UserManagerTest extends AbstractTestCase
{
    private ?UserManager $userManager;

    protected function setUp(): void
    {
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