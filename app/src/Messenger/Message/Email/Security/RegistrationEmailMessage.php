<?php

declare(strict_types=1);

namespace App\Messenger\Message\Email\Security;

use App\Messenger\Message\Shared\SyncMessageInterface;

/**
 * @warning Do not async this message, it is used to send the password in plain text.
 */
class RegistrationEmailMessage implements SyncMessageInterface
{
    public function __construct(
        private readonly int $userId,
        private readonly string $plainPassword,
    ) {}

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}
