<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Service\Security\SecurityManager;
use App\Service\User\UserActivityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class UserEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly SecurityManager $securityManager,
        private readonly UserActivityManager $userActivityManager
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        if (HttpKernelInterface::MAIN_REQUEST !== $event->getRequestType()) {
            return;
        }

        try {
            $user = $this->securityManager->getConnectedUser();
        } catch (AccessDeniedHttpException) {
            return;
        }

        $this->userActivityManager->updateLastActivity($user);
    }
}
