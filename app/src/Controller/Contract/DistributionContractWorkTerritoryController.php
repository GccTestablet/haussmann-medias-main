<?php

declare(strict_types=1);

namespace App\Controller\Contract;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Contract\DistributionContract;
use App\Entity\Work\Work;
use App\Form\DtoFactory\Contract\DistributionContractWorkTerritoryFormDtoFactory;
use App\Form\Handler\Contract\DistributionContractWorkTerritoryFormHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/distribution-contracts/{contract}/work/{work}/territories')]
class DistributionContractWorkTerritoryController extends AbstractAppController
{
    public function __construct(
        private readonly DistributionContractWorkTerritoryFormDtoFactory $formDtoFactory,
        private readonly DistributionContractWorkTerritoryFormHandler $formHandler,
    ) {}

    #[Route(path: '/manage', name: 'app_distribution_contract_work_territory_manage')]
    public function manage(Request $request, DistributionContract $contract, Work $work): Response
    {
        $contractWork = $contract->getContractWork($work);
        if (!$contractWork) {
            throw $this->createNotFoundException(\sprintf('Work %d not found in contract %d.', $work->getId(), $contract->getId()));
        }

        $dto = $this->formDtoFactory->create($contractWork);

        $formHandlerResponse = $this->formHandlerManager->createAndHandle(
            $this->formHandler,
            $request,
            $dto
        );

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_distribution_contract_show', [
                'id' => $contract->getId(),
                'tab' => 'works',
            ]);
        }

        return $this->render('distribution_contract/work/territory/manage.html.twig', [
            'contract' => $contract,
            'work' => $work,
            'form' => $form,
        ]);
    }
}
