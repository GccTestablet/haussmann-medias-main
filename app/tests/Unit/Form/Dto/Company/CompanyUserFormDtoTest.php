<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form\Dto\Company;

use App\Entity\Company;
use App\Entity\User;
use App\Enum\User\UserCompanyPermissionEnum;
use App\Form\Dto\Company\CompanyUserFormDto;
use App\Tests\Shared\AbstractTestCase;

class CompanyUserFormDtoTest extends AbstractTestCase
{
    public function testGetAndSetCompany(): void
    {
        $company = new Company();
        $dto = new CompanyUserFormDto($company);

        $this->assertSame($company, $dto->getCompany());
    }

    public function testGetAndSetUser(): void
    {
        $user = new User();
        $dto = new CompanyUserFormDto(new Company());

        $this->assertNull($dto->getUser());

        $dto->setUser($user);

        $this->assertSame($user, $dto->getUser());
    }

    public function testGetAndSetPermission(): void
    {
        $dto = new CompanyUserFormDto(new Company());

        $this->assertNull($dto->getPermission());

        $dto->setPermission(UserCompanyPermissionEnum::ADMIN);

        $this->assertSame(UserCompanyPermissionEnum::ADMIN, $dto->getPermission());
    }
}
