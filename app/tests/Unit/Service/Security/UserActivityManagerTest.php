<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security;

use App\Entity\User;
use App\Service\User\UserActivityManager;
use App\Tests\AbstractTestCase;
use App\Tools\Parser\DateParser;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;

class UserActivityManagerTest extends AbstractTestCase
{
    private EntityManagerInterface|MockObject|null $entityManager;

    private DateParser|MockObject|null $dateParser;

    private ?UserActivityManager $userActivityManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->dateParser = $this->createMock(DateParser::class);

        $this->userActivityManager = new UserActivityManager(
            $this->entityManager,
            $this->dateParser
        );
    }

    public function testUpdateLastLogin(): void
    {
        $user = new User();
        $this->assertNull($user->getLastLogin());

        $dateTime = new \DateTime();
        $this->dateParser->method('getDateTime')->willReturn($dateTime);
        $this->entityManager->expects($this->once())->method('flush');

        $this->userActivityManager->updateLastLogin($user);

        $this->assertSame($dateTime, $user->getLastLogin());
    }

    public function testUpdateLastActivity(): void
    {
        $user = new User();
        $this->assertNull($user->getLastActivity());

        $dateTime = new \DateTime();
        $this->dateParser->method('getDateTime')->willReturn($dateTime);
        $this->entityManager->expects($this->once())->method('flush');

        $this->userActivityManager->updateLastActivity($user);

        $this->assertSame($dateTime, $user->getLastActivity());
    }
}
