<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Security;

use App\Entity\User;
use App\Service\User\UserActivityManager;
use App\Tests\Shared\AbstractTestCase;
use App\Tools\Parser\DateParser;
use Doctrine\ORM\EntityManagerInterface;
use Prophecy\Prophecy\ObjectProphecy;

class UserActivityManagerTest extends AbstractTestCase
{
    private EntityManagerInterface|ObjectProphecy|null $entityManager;

    private DateParser|ObjectProphecy|null $dateParser;

    private ?UserActivityManager $userActivityManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->prophesize(EntityManagerInterface::class);
        $this->dateParser = $this->prophesize(DateParser::class);

        $this->userActivityManager = new UserActivityManager(
            $this->entityManager->reveal(),
            $this->dateParser->reveal()
        );
    }

    public function testUpdateLastLogin(): void
    {
        $user = new User();
        $this->assertNull($user->getLastLogin());

        $dateTime = new \DateTime();
        $this->dateParser->getDateTime()->willReturn($dateTime);
        $this->entityManager->flush()->shouldBeCalled();

        $this->userActivityManager->updateLastLogin($user);

        $this->assertSame($dateTime, $user->getLastLogin());
    }

    public function testUpdateLastActivity(): void
    {
        $user = new User();
        $this->assertNull($user->getLastActivity());

        $dateTime = new \DateTime();
        $this->dateParser->getDateTime()->willReturn($dateTime);
        $this->entityManager->flush()->shouldBeCalled();

        $this->userActivityManager->updateLastActivity($user);

        $this->assertSame($dateTime, $user->getLastActivity());
    }
}
