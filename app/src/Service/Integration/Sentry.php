<?php

declare(strict_types=1);

namespace App\Service\Integration;

use App\Service\Security\SecurityManager;
use Sentry\Event;
use Sentry\UserDataBag;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class Sentry
{
    public function __construct(
        private readonly SecurityManager $securityManager,
        private readonly RequestStack $requestStack
    ) {}

    public function getBeforeSend(): callable
    {
        return function (Event $event): Event {
            $userData = null;
            try {
                $user = $this->securityManager->getConnectedUser();
                $userData = new UserDataBag($user->getId());

                $request = $this->requestStack->getMainRequest();
                if ($request) {
                    $userData->setIpAddress($request->getClientIp());
                }
            } catch (AccessDeniedHttpException) {
            }

            $event->setUser($userData);

            return $event;
        };
    }
}
