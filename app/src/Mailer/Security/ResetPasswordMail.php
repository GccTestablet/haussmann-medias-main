<?php

declare(strict_types=1);

namespace App\Mailer\Security;

use App\Entity\User;
use App\Mailer\Shared\MailInterface;
use App\Mailer\Shared\Mime\Template;
use Symfony\Component\Mime\Address;

class ResetPasswordMail implements MailInterface
{
    public function __construct(
        private readonly User $user,
        private readonly string $confirmationUrl,
    ) {}

    public function to(): array
    {
        return [new Address($this->user->getEmail(), $this->user->getFullName())];
    }

    public function subject(): string
    {
        return 'Réinitialisation de votre mot de passe';
    }

    public function template(): Template
    {
        return new Template(
            'mail/security/reset_password.mjml.twig',
            [
                'user' => $this->user,
                'confirmationUrl' => $this->confirmationUrl,
            ]
        );
    }
}
