<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Company;

use App\Entity\Company;
use App\Entity\Contract;
use App\Form\Dto\Company\CompanyContractFormDto;
use App\Tools\Manager\UploadFileManager;
use App\Tools\Parser\DateParser;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CompanyContractFormDtoFactory
{
    public function __construct(
        private readonly DateParser $dateParser,
        private readonly UploadFileManager $uploadFileManager
    ) {}

    public function create(Company $company, ?Contract $contract): CompanyContractFormDto
    {
        if (!$contract) {
            $contract = (new Contract())
                ->setCompany($company)
            ;

            return new CompanyContractFormDto($contract, false);
        }

        $filePath = $this->uploadFileManager->path($contract->getUploadDir(), $contract->getFileName());
        $file = new UploadedFile(
            $filePath,
            $contract->getOriginalFileName()
        );

        return (new CompanyContractFormDto($contract, true))
            ->setBeneficiary($contract->getBeneficiary())
            ->setFile($file)
            ->setSignedAt($contract->getSignedAt())
            ->setStartsAt($contract->getStartsAt())
            ->setEndsAt($contract->getEndsAt())
            ->setTerritories($contract->getTerritories())
        ;
    }

    public function updateEntity(CompanyContractFormDto $dto, Contract $contract): void
    {
        if ($dto->getFile()) {
            $now = $this->dateParser->getDateTime()?->getTimestamp();
            $extension = $dto->getFile()->getClientOriginalExtension();
            $contract
                ->setOriginalFileName($dto->getFile()->getClientOriginalName())
                ->setFileName(\sprintf('%s.%s', $now, $extension))
            ;
        }

        $contract
            ->setBeneficiary($dto->getBeneficiary())
            ->setSignedAt($dto->getSignedAt())
            ->setStartsAt($dto->getStartsAt())
            ->setEndsAt($dto->getEndsAt())
            ->setTerritories($dto->getTerritories())
        ;
    }
}
