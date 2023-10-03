<?php

declare(strict_types=1);

namespace App\EventSubscriber\Mailer;

use App\Event\Mailer\Security\RegistrationEvent;
use App\Event\Mailer\Security\ResetPasswordEvent;
use App\Mailer\MailSender;
use App\Mailer\Security\RegisterMail;
use App\Mailer\Security\ResetPasswordMail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecurityEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly MailSender $mailSender,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            RegistrationEvent::class => 'onRegistration',
            ResetPasswordEvent::class => 'onResetPassword',
        ];
    }

    public function onRegistration(RegistrationEvent $event): void
    {
        $mail = new RegisterMail($event->getUser(), $event->getPlainPassword());

        $this->mailSender->send($mail);
    }

    public function onResetPassword(ResetPasswordEvent $event): void
    {
        $user = $event->getUser();
        $confirmationUrl = $this->urlGenerator->generate('app_security_resetting_reset_password', [
            'confirmationToken' => $user->getPasswordResetToken(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        $this->mailSender->send(new ResetPasswordMail($user, $confirmationUrl));
    }
}
