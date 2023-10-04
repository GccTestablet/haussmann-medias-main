<?php

declare(strict_types=1);

namespace App\Enum;

enum UserCompanyPermissionEnum: string
{
    case ADMIN = 'admin';
    case READ_ONLY = 'read_only';
    case READ_WRITE = 'read_write';

    public function getAsText(): string
    {
        return match ($this) {
            self::ADMIN => 'Admin',
            self::READ_ONLY => 'Read only',
            self::READ_WRITE => 'Read write',
        };
    }
}
