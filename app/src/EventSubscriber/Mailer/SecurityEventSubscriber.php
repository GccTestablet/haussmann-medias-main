<?php

declare(strict_types=1);

namespace App\EventSubscriber\Mailer;

use App\Event\Mailer\RegistrationEvent;
use App\Mailer\MailSender;
use App\Mailer\Security\RegisterMail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SecurityEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly MailSender $mailSender,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            RegistrationEvent::class => 'onRegistration',
        ];
    }

    public function onRegistration(RegistrationEvent $event): void
    {
        $mail = new RegisterMail($event->getUser(), $event->getPlainPassword());

        $this->mailSender->send($mail);
    }
}
