<?php

declare(strict_types=1);

namespace App\Mailer\Shared;

use App\Mailer\Shared\Mime\Template;
use Symfony\Component\Mime\Address;

interface MailInterface
{
    public const SENDER_EMAIL = 'no-reply@haussmann-medias.com';
    public const SENDER_NAME = 'Haussmann Medias';

    public function from(): Address;

    /**
     * @return Address[]
     */
    public function to(): array;

    public function subject(): string;

    public function template(): Template;
}
