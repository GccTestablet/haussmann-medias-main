<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Security;

use App\Entity\User;
use App\Service\Security\SecurityManager;
use App\Tests\Fixtures\Doctrine\UserFixture;
use App\Tests\Shared\AbstractTestCase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SecurityManagerTest extends AbstractTestCase
{
    private ?SecurityManager $securityManager;

    protected function setUp(): void
    {
        $this->loadOrmOnDemandFixtures([
            UserFixture::class,
        ]);

        $this->securityManager = $this->getService(SecurityManager::class);
    }

    public function testGetUser(): void
    {
        /** @var User $user */
        $user = $this->getReference(UserFixture::SUPER_ADMIN_USER);
        $this->logInAsUser($user);

        $this->assertSame($user, $this->securityManager->getConnectedUser());
    }

    public function testGetUserThrowException(): void
    {
        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('The connected user is invalid');

        $this->securityManager->getConnectedUser();
    }

    public function testGetUserRole(): void
    {
        /** @var User $user */
        $user = $this->getReference(UserFixture::SUPER_ADMIN_USER);

        $this->assertSame('ROLE_SUPER_ADMIN', $this->securityManager->getUserRole($user));
    }

    public function testHasRole(): void
    {
        $this->logInAs(UserFixture::SUPER_ADMIN_USER);

        $this->assertTrue($this->securityManager->hasRole('ROLE_SUPER_ADMIN'));
        $this->assertFalse($this->securityManager->hasRole('ROLE_NOT_EXISTING'));
    }
}
