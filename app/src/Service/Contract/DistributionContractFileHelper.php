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
            $trashIconElement = $this->HTMLDomParser->createElement('i', ['class' => 'fas fa-trash-alt']);
            $aRemoveElement = $this->HTMLDomParser->createElement('a', [
                'href' => $this->urlGenerator->generate('app_distribution_contract_file_remove', [
                    'contract' => $contract->getId(),
                    'file' => $contractFile->getId(),
                ]),
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
}
