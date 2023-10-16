<?php

declare(strict_types=1);

namespace App\Controller\Contract;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Contract\DistributionContractWork;
use App\Form\DtoFactory\Contract\DistributionContractWorkTerritoryFormDtoFactory;
use App\Form\Handler\Contract\DistributionContractWorkTerritoryFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/distribution-contracts/{contract}/work/{work}')]
class DistributionContractWorkTerritoryController extends AbstractAppController
{
    public function __construct(
        private readonly DistributionContractWorkTerritoryFormDtoFactory $formDtoFactory,
        private readonly DistributionContractWorkTerritoryFormHandler $formHandler,
    ) {}

    #[Route('/add', name: 'app_distribution_contract_work_territory_add')]
    public function add(Request $request, DistributionContractWork $distributionContractWork): Response
    {
        //        $formHandlerResponse = $this->getFormHandlerResponse($request, $distributionContractWork);

        //        $form = $formHandlerResponse->getForm();
        //        if ($formHandlerResponse->isSuccessful()) {
        //            return $this->redirectToRoute('app_distribution_contract_show', [
        //                'id' => $distributionContractWork->getDistributionContract()->getId(),
        //            ]);
        //        }

        return $this->render('shared/common/component.html.twig', [
            'title' => 'Add territory',
            'componentName' => 'distribution_contract_work_territory_form',
            'componentParameters' => [
                'contractWork' => $distributionContractWork,
            ],
//            'contractWork' => $distributionContractWork,
//            'form' => $form->createView(),
        ]);
    }

    private function getFormHandlerResponse(Request $request, DistributionContractWork $distributionContractWork): FormHandlerResponseInterface
    {
        $dto = $this->formDtoFactory->create($distributionContractWork);

        return $this->formHandlerManager->createAndHandle(
            $this->formHandler,
            $request,
            $dto
        );
    }
}
