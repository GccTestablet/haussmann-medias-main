<?php

declare(strict_types=1);

namespace App\Tests\Unit\EventSubscriber;

use App\Entity\User;
use App\EventSubscriber\UserEventSubscriber;
use App\Service\Security\SecurityManager;
use App\Service\User\UserActivityManager;
use App\Tests\Shared\AbstractTestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class UserEventSubscriberTest extends AbstractTestCase
{
    private SecurityManager|ObjectProphecy|null $securityManager;
    private UserActivityManager|ObjectProphecy|null $userActivityManager;
    private ?UserEventSubscriber $userEventSubscriber;

    protected function setUp(): void
    {
        $this->securityManager = $this->prophesize(SecurityManager::class);
        $this->userActivityManager = $this->prophesize(UserActivityManager::class);

        $this->userEventSubscriber = new UserEventSubscriber(
            $this->securityManager->reveal(),
            $this->userActivityManager->reveal()
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
        $this->securityManager->getConnectedUser()->shouldNotBeCalled();
        $this->userActivityManager->updateLastActivity()->shouldNotBeCalled();

        $event = new ControllerEvent(
            $this->prophesize(HttpKernelInterface::class)->reveal(),
            fn () => null,
            $this->prophesize(Request::class)->reveal(),
            HttpKernelInterface::SUB_REQUEST
        );

        $this->userEventSubscriber->onKernelController($event);
    }

    public function testOnKernelControllerWithoutConnectedUser(): void
    {
        $this->securityManager->getConnectedUser()->willThrow(new AccessDeniedHttpException());
        $this->userActivityManager->updateLastActivity()->shouldNotBeCalled();

        $event = new ControllerEvent(
            $this->prophesize(HttpKernelInterface::class)->reveal(),
            fn () => null,
            $this->prophesize(Request::class)->reveal(),
            HttpKernelInterface::MAIN_REQUEST
        );

        $this->userEventSubscriber->onKernelController($event);
    }

    public function testOnKernelControllerWithUserAndMainRequest(): void
    {
        $user = new User();
        $this->securityManager->getConnectedUser()->willReturn($user);
        $this->userActivityManager->updateLastActivity($user)->shouldBeCalled();

        $event = new ControllerEvent(
            $this->prophesize(HttpKernelInterface::class)->reveal(),
            fn () => null,
            $this->prophesize(Request::class)->reveal(),
            HttpKernelInterface::MAIN_REQUEST
        );

        $this->userEventSubscriber->onKernelController($event);
    }
}
