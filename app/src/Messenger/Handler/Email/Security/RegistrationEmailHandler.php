<?php

declare(strict_types=1);

namespace App\Messenger\Handler\Email\Security;

use App\Mailer\MailSender;
use App\Mailer\Security\RegisterMail;
use App\Messenger\Message\Email\Security\RegistrationEmailMessage;
use App\Service\User\UserManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RegistrationEmailHandler
{
    public function __construct(
        private readonly UserManager $userManager,
        private readonly MailSender $mailSender,
    ) {}

    public function __invoke(RegistrationEmailMessage $message): void
    {
        $user = $this->userManager->find($message->getUserId());
        if (!$user) {
            throw new NotFoundHttpException(\sprintf('User "%d" not found', $message->getUserId()));
        }

        $this->mailSender->send(new RegisterMail($user, $message->getPlainPassword()));
    }
}
