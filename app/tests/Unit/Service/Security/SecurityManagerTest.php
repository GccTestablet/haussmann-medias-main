<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security;

use App\Entity\User;
use App\Service\Security\SecurityManager;
use App\Tests\Shared\AbstractTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\SecurityBundle\Security;

class SecurityManagerTest extends AbstractTestCase
{
    private Security|MockObject|null $security;

    private ?SecurityManager $securityManager;

    protected function setUp(): void
    {
        $this->security = $this->createMock(Security::class);
        $this->securityManager = new SecurityManager(
            $this->security
        );
    }

    public function testGetConnectedUser(): void
    {
        $user = new User();
        $this->security->method('getUser')->willReturn($user);

        $this->assertSame($user, $this->securityManager->getConnectedUser());
    }

    public function testGetConnectedUserThrowException(): void
    {
        $this->security->method('getUser')->willReturn(null);
        $this->expectExceptionMessage('The connected user is invalid');

        $this->securityManager->getConnectedUser();
    }
}
