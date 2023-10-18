<?php

declare(strict_types=1);

namespace App\Service\Contract;

use App\Entity\Contract\DistributionContract;
use App\Tools\Parser\HTMLDomParser;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DistributionContractFileHelper
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly HTMLDomParser $HTMLDomParser,
    ) {}

    /**
     * @return string[]
     */
    public function getFilesHelper(DistributionContract $contract): array
    {
        $files = [];
        foreach ($contract->getContractFiles() as $contractFile) {
            $divElement = $this->HTMLDomParser->createElement('div', ['class' => 'mt-1']);
            $aRemoveElement = $this->HTMLDomParser->createElement('a', [
                'href' => $this->urlGenerator->generate('app_distribution_contract_file_remove', [
                    'contract' => $contract->getId(),
                    'file' => $contractFile->getId(),
                ]),
                'title' => 'Remove',
                'class' => 'btn btn-xs btn-danger me-1',
            ], 'X');

            $aDownloadElement = $this->HTMLDomParser->createElement('a', [
                'href' => $this->urlGenerator->generate('app_distribution_contract_file_download', [
                    'contract' => $contract->getId(),
                    'file' => $contractFile->getId(),
                ]),
                'title' => 'Download',
                'class' => 'btn btn-xs btn-primary me-1',
            ], 'D');

            $this->HTMLDomParser->appendTo($divElement, [
                $aRemoveElement,
                $aDownloadElement,
                ' ',
                $contractFile->getOriginalFileName(),
            ]);

            $files[] = $this->HTMLDomParser->asString($divElement);
        }

        return $files;
    }
}
