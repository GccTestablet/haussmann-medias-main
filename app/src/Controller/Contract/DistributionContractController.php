<?php

declare(strict_types=1);

namespace App\Controller\Contract;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Contract\DistributionContract;
use App\Service\Contract\DistributionContractTemplateGenerator;
use App\Tools\Manager\UploadFileManager;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/distribution-contracts')]
class DistributionContractController extends AbstractAppController
{
    public function __construct(
        private readonly UploadFileManager $uploadFileManager,
        private readonly DistributionContractTemplateGenerator $distributionContractTemplateGenerator
    ) {}

    #[Route(path: '/{id}', name: 'app_distribution_contract_show', requirements: ['id' => '\d+'])]
    public function show(DistributionContract $contract): Response
    {
        return $this->render('distribution_contract/show.html.twig', [
            'contract' => $contract,
        ]);
    }

    #[Route(path: '/{id}/generate-template', name: 'app_distribution_contract_generate_template', requirements: ['id' => '\d+'])]
    public function generateTemplate(DistributionContract $contract): BinaryFileResponse
    {
        $fileName = $this->uploadFileManager->createTempFile(\sprintf('template-%s.csv', $contract->getId()));
        $this->distributionContractTemplateGenerator->generate($contract, $fileName);

        $response = new BinaryFileResponse($fileName, Response::HTTP_OK, [
            'Cache-Control' => 'private',
            'Content-type' => 'text/csv',
            'Content-Disposition' => \sprintf('attachment; filename="Modèle contrat %s.csv";', $contract->getDistributor()->getName()),
        ]);

        $response->sendHeaders();

        return $response;
    }
}
