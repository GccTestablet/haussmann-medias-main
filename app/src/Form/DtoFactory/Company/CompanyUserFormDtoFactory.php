<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Company;

use App\Entity\Company;
use App\Entity\UserCompany;
use App\Form\Dto\Company\CompanyUserFormDto;

class CompanyUserFormDtoFactory
{
    public function create(Company $company): CompanyUserFormDto
    {
        return new CompanyUserFormDto($company);
    }

    public function updateCompanyUser(CompanyUserFormDto $dto, Company $company): void
    {
        $user = (new UserCompany($dto->getUser(), $company))
            ->setPermission($dto->getPermission())
        ;

        $company
            ->addUser($user)
        ;
    }
}
