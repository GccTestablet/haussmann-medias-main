<?php

declare(strict_types=1);

namespace App\Mailer\Security;

use App\Entity\User;
use App\Mailer\Shared\MailInterface;
use App\Mailer\Shared\Mime\Template;
use Symfony\Component\Mime\Address;

class RegisterMail implements MailInterface
{
    public function __construct(
        private readonly User $user,
        private readonly string $plainPassword
    ) {}

    public function to(): array
    {
        return [new Address($this->user->getEmail(), $this->user->getLastName())];
    }

    public function subject(): string
    {
        return 'Création de votre compte';
    }

    public function template(): Template
    {
        return new Template(
            'mail/security/register.mjml.twig',
            [
                'user' => $this->user,
                'plainPassword' => $this->plainPassword,
            ]
        );
    }
}
