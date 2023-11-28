<?php

declare(strict_types=1);

namespace App\Messenger\Handler\Email\Security;

use App\Mailer\MailSender;
use App\Mailer\Security\ResetPasswordMail;
use App\Messenger\Message\Email\Security\ResetPasswordEmailMessage;
use App\Service\User\UserManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

#[AsMessageHandler]
class ResetPasswordEmailHandler
{
    public function __construct(
        private readonly RouterInterface $router,
        private readonly UserManager $userManager,
        private readonly MailSender $mailSender,
    ) {}

    public function __invoke(ResetPasswordEmailMessage $message): void
    {
        $user = $this->userManager->find($message->getUserId());
        if (!$user) {
            throw new NotFoundHttpException(\sprintf('User "%d" not found', $message->getUserId()));
        }

        $confirmationUrl = $this->router->generate('app_security_resetting_reset_password', [
            'confirmationToken' => $user->getPasswordResetToken(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $this->mailSender->send(new ResetPasswordMail($user, $confirmationUrl));
    }
}
