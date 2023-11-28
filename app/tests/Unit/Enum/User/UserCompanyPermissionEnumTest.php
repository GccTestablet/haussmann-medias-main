<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum\User;

use App\Enum\User\UserCompanyPermissionEnum;
use App\Tests\Unit\Enum\AbstractEnumTestCase;

class UserCompanyPermissionEnumTest extends AbstractEnumTestCase
{
    protected static string $enum = UserCompanyPermissionEnum::class;

    public function testNames(): void
    {
        $this->assertAsCallback(
            [
                'ADMIN',
                'READ_ONLY',
                'READ_WRITE',
            ],
            fn (UserCompanyPermissionEnum $userCompanyPermissionEnum) => $userCompanyPermissionEnum->name
        );
    }

    public function testValues(): void
    {
        $this->assertAsCallback(
            [
                'admin',
                'read_only',
                'read_write',
            ],
            fn (UserCompanyPermissionEnum $userCompanyPermissionEnum) => $userCompanyPermissionEnum->value
        );
    }

    public function testGetAsText(): void
    {
        $this->assertAsCallback(
            [
                'Admin',
                'Read only',
                'Read write',
            ],
            fn (UserCompanyPermissionEnum $userCompanyPermissionEnum) => $userCompanyPermissionEnum->getAsText()
        );
    }
}
