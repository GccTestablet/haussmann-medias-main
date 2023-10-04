<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Beneficiary;

use App\Entity\Beneficiary;
use App\Form\Dto\Beneficiary\BeneficiaryFormDto;

class BeneficiaryFormDtoFactory
{
    public function create(?Beneficiary $beneficiary): BeneficiaryFormDto
    {
        if (!$beneficiary) {
            return new BeneficiaryFormDto(new Beneficiary(), false);
        }

        return (new BeneficiaryFormDto($beneficiary, true))
            ->setName($beneficiary->getName())
        ;
    }

    public function updateEntity(BeneficiaryFormDto $dto, Beneficiary $beneficiary): void
    {
        $beneficiary
            ->setName($dto->getName())
        ;
    }
}
