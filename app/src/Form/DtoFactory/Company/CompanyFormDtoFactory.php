<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Company;

use App\Entity\Company;
use App\Form\Dto\Company\CompanyFormDto;

class CompanyFormDtoFactory
{
    public function create(?Company $company): CompanyFormDto
    {
        if (!$company) {
            return new CompanyFormDto(new Company(), false);
        }

        return (new CompanyFormDto($company, true))
            ->setName($company->getName())
            ->setType($company->getType())
        ;
    }

    public function updateCompany(CompanyFormDto $dto, Company $company): void
    {
        $company
            ->setName($dto->getName())
            ->setType($dto->getType())
        ;
    }
}
