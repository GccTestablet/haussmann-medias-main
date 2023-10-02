<?php

declare(strict_types=1);

namespace App\Mailer\Admin;

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

    public function from(): Address
    {
        return new Address(self::SENDER_EMAIL, self::SENDER_NAME);
    }

    public function to(): array
    {
        return [new Address($this->user->getEmail(), $this->user->getLastName())];
    }

    public function subject(): string
    {
        return \sprintf('Création de votre compte %s', self::SENDER_NAME);
    }

    public function template(): Template
    {
        return new Template(
            'mail/admin/register.mjml.twig',
            [
                'user' => $this->user,
                'plainPassword' => $this->plainPassword,
                'siteName' => self::SENDER_NAME,
            ]
        );
    }
}
