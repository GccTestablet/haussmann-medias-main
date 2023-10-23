<?php

declare(strict_types=1);

namespace App\Service\Contract;

use App\Entity\Contract\AcquisitionContract;
use App\Entity\Contract\AcquisitionContractFile;
use App\Entity\Contract\DistributionContract;
use App\Entity\Contract\DistributionContractFile;
use App\Tools\Parser\HTMLDomParser;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ContractFileHelper
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly HTMLDomParser $HTMLDomParser,
    ) {}

    /**
     * @return string[]
     */
    public function getFilesHelper(AcquisitionContract|DistributionContract $contract): array
    {
        $files = [];
        foreach ($contract->getContractFiles() as $contractFile) {
            $divElement = $this->HTMLDomParser->createElement('div', ['class' => 'mt-1']);
            $trashIconElement = $this->HTMLDomParser->createElement('i', ['class' => 'fas fa-trash-alt']);
            $aRemoveElement = $this->HTMLDomParser->createElement('a', [
                'href' => $this->getUrl($contract, $contractFile),
                'title' => 'Remove',
                'class' => 'btn btn-sm btn-danger me-1',
            ], $trashIconElement);

            $this->HTMLDomParser->appendTo($divElement, [
                $aRemoveElement,
                ' ',
                $contractFile->getOriginalFileName(),
            ]);

            $files[] = $this->HTMLDomParser->asString($divElement);
        }

        return $files;
    }

    private function getUrl(AcquisitionContract|DistributionContract $contract, AcquisitionContractFile|DistributionContractFile $contractFile): string
    {
        if ($contract instanceof AcquisitionContract) {
            return $this->urlGenerator->generate('app_acquisition_contract_file_remove', [
                'contract' => $contract->getId(),
                'file' => $contractFile->getId(),
            ]);
        }

        return $this->urlGenerator->generate('app_distribution_contract_file_remove', [
            'contract' => $contract->getId(),
            'file' => $contractFile->getId(),
        ]);
    }
}
