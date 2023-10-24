<?php

declare(strict_types=1);

namespace App\Tests\Unit\EventSubscriber;

use App\Entity\User;
use App\EventSubscriber\UserEventSubscriber;
use App\Service\Security\SecurityManager;
use App\Service\User\UserActivityManager;
use App\Tests\Shared\AbstractTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class UserEventSubscriberTest extends AbstractTestCase
{
    private SecurityManager|MockObject|null $securityManager;
    private UserActivityManager|MockObject|null $userActivityManager;
    private ?UserEventSubscriber $userEventSubscriber;

    protected function setUp(): void
    {
        $this->securityManager = $this->createMock(SecurityManager::class);
        $this->userActivityManager = $this->createMock(UserActivityManager::class);

        $this->userEventSubscriber = new UserEventSubscriber(
            $this->securityManager,
            $this->userActivityManager
        );
    }

    public function testGetSubscribedEvents(): void
    {
        $this->assertSame([
            'kernel.controller' => 'onKernelController',
        ], UserEventSubscriber::getSubscribedEvents());
    }

    public function testOnKernelControllerWithSubRequest(): void
    {
        $this->securityManager->expects($this->never())->method('getConnectedUser');
        $this->userActivityManager->expects($this->never())->method('updateLastActivity');

        $event = new ControllerEvent(
            $this->createMock(HttpKernelInterface::class),
            fn () => null,
            $this->createMock(Request::class),
            HttpKernelInterface::SUB_REQUEST
        );

        $this->userEventSubscriber->onKernelController($event);
    }

    public function testOnKernelControllerWithoutConnectedUser(): void
    {
        $this->securityManager->expects($this->once())->method('getConnectedUser')->willThrowException(new AccessDeniedHttpException());
        $this->userActivityManager->expects($this->never())->method('updateLastActivity');

        $event = new ControllerEvent(
            $this->createMock(HttpKernelInterface::class),
            fn () => null,
            $this->createMock(Request::class),
            HttpKernelInterface::MAIN_REQUEST
        );

        $this->userEventSubscriber->onKernelController($event);
    }

    public function testOnKernelControllerWithUserAndMainRequest(): void
    {
        $user = new User();
        $this->securityManager->expects($this->once())->method('getConnectedUser')->willReturn($user);
        $this->userActivityManager->expects($this->once())->method('updateLastActivity')->with($user);

        $event = new ControllerEvent(
            $this->createMock(HttpKernelInterface::class),
            fn () => null,
            $this->createMock(Request::class),
            HttpKernelInterface::MAIN_REQUEST
        );

        $this->userEventSubscriber->onKernelController($event);
    }
}
