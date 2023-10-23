<?php

declare(strict_types=1);

namespace App\Controller\Contract;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Contract\DistributionContract;
use App\Entity\User;
use App\Form\DtoFactory\Contract\DistributionContractFormDtoFactory;
use App\Form\Handler\Contract\DistributionContractFormHandler;
use App\Security\Voter\CompanyVoter;
use App\Service\Contract\DistributionContractWorkManager;
use App\Service\Contract\DistributionContractWorkRevenueImporter;
use App\Tools\Manager\UploadFileManager;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/distribution-contracts')]
class DistributionContractController extends AbstractAppController
{
    public function __construct(
        private readonly UploadFileManager $uploadFileManager,
        private readonly DistributionContractWorkRevenueImporter $distributionContractTemplateGenerator,
        private readonly DistributionContractWorkManager $distributionContractWorkManager
    ) {}

    #[Route(path: '/{id}', name: 'app_distribution_contract_show', requirements: ['id' => '\d+'])]
    public function show(DistributionContract $contract): Response
    {
        return $this->render('distribution_contract/show.html.twig', [
            'contract' => $contract,
            'contractWorks' => $this->distributionContractWorkManager->findByDistributionContract($contract),
        ]);
    }

    #[Route(path: '/{id}/update', name: 'app_distribution_contract_update', requirements: ['id' => '\d+'])]
    #[IsGranted(User::ROLE_ADMIN)]
    public function update(Request $request, DistributionContractFormDtoFactory $formDtoFactory, DistributionContractFormHandler $formHandler, DistributionContract $contract): Response
    {
        $company = $contract->getCompany();
        $this->denyAccessUnlessGranted(CompanyVoter::COMPANY_ADMIN, $company);

        $dto = $formDtoFactory->create($company, $contract);
        $formHandlerResponse = $this->formHandlerManager->createAndHandle(
            $formHandler,
            $request,
            $dto
        );

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_distribution_contract_show', ['id' => $contract->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update contract %contract% from company %company%', [
                '%contract%' => $contract->getName(),
                '%company%' => $company->getName(),
            ], 'company'),
            'form' => $form,
        ]);
    }

    #[Route(path: '/{id}/generate-template', name: 'app_distribution_contract_generate_template', requirements: ['id' => '\d+'])]
    public function generateTemplate(DistributionContract $contract): BinaryFileResponse
    {
        $fileName = $this->uploadFileManager->createTempFile(\sprintf('template-%s.csv', $contract->getId()));
        $this->distributionContractTemplateGenerator->build(['contract' => $contract]);
        $this->distributionContractTemplateGenerator->generateTemplate($fileName);

        $response = new BinaryFileResponse($fileName, Response::HTTP_OK, [
            'Cache-Control' => 'private',
            'Content-type' => 'text/csv',
            'Content-Disposition' => \sprintf('attachment; filename="Modèle contrat %s.csv";', $contract->getDistributor()->getName()),
        ]);

        $response->sendHeaders();

        return $response;
    }
}
