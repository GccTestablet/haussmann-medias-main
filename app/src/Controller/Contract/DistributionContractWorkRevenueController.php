<?php

declare(strict_types=1);

namespace App\Controller\Contract;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Contract\DistributionContract;
use App\Form\DtoFactory\Contract\DistributionContractWorkRevenueImportFormDtoFactory;
use App\Form\Handler\Contract\DistributionContractWorkImportFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/distribution-contracts/{contract}/work/revenues')]
class DistributionContractWorkRevenueController extends AbstractAppController
{
    public function __construct(
        private readonly DistributionContractWorkRevenueImportFormDtoFactory $formDtoFactory,
        private readonly DistributionContractWorkImportFormHandler $formHandler,
    ) {}

    #[Route('/import', name: 'app_distribution_contract_work_revenue_import')]
    public function import(Request $request, DistributionContract $contract): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, $contract);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_distribution_contract_show', ['id' => $contract->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Import', [], 'contract'),
            'form' => $form,
        ]);
    }

    private function getFormHandlerResponse(Request $request, DistributionContract $distributionContract): FormHandlerResponseInterface
    {
        $dto = $this->formDtoFactory->create($distributionContract);

        return $this->formHandlerManager->createAndHandle(
            $this->formHandler,
            $request,
            $dto
        );
    }
}
