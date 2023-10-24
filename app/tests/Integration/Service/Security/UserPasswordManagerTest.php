<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Security;

use App\Entity\User;
use App\Service\Security\UserPasswordManager;
use App\Tests\Fixtures\Doctrine\UserFixture;
use App\Tests\Shared\AbstractTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class UserPasswordManagerTest extends AbstractTestCase
{
    private ?UserPasswordManager $userPasswordManager;

    private ?UserPasswordHasher $userPasswordHasher;

    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            UserFixture::class,
        ]);

        $this->userPasswordManager = $this->getService(UserPasswordManager::class);
        $this->userPasswordHasher = $this->getService(UserPasswordHasher::class);
    }

    public function testUpdatePassword(): void
    {
        /** @var User $user */
        $user = $this->getReference(UserFixture::SUPER_ADMIN_USER);

        $this->userPasswordManager->updatePassword($user, 'new-password');

        $this->assertTrue($this->userPasswordHasher->isPasswordValid($user, 'new-password'));
        $this->assertFalse($this->userPasswordHasher->isPasswordValid($user, 'password'));
    }
}
