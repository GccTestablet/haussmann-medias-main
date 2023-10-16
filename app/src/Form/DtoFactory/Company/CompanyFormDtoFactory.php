<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Company;

use App\Entity\Company;
use App\Form\Dto\Company\CompanyFormDto;
use App\Tools\Parser\ObjectParser;

class CompanyFormDtoFactory
{
    public function __construct(
        private readonly ObjectParser $objectParser
    ) {}

    public function create(?Company $company): CompanyFormDto
    {
        if (!$company) {
            return new CompanyFormDto(new Company(), false);
        }

        $dto = new CompanyFormDto($company, true);
        $this->objectParser->mergeFromObject($company, $dto);

        return $dto;
    }

    public function updateCompany(CompanyFormDto $dto, Company $company): void
    {
        $this->objectParser->mergeFromObject($dto, $company);
    }
}
