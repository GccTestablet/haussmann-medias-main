<?php

declare(strict_types=1);

namespace App\Messenger\Message\Email\Security;

use App\Messenger\Message\Shared\AsyncMessageInterface;

class ResetPasswordEmailMessage implements AsyncMessageInterface
{
    public function __construct(
        private readonly int $userId,
    ) {}

    public function getUserId(): int
    {
        return $this->userId;
    }
}
