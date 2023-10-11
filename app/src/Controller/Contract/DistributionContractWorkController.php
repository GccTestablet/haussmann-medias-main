<?php

declare(strict_types=1);

namespace App\Controller\Contract;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Contract\DistributionContract;
use App\Entity\Contract\DistributionContractWork;
use App\Form\Dto\Work\WorkFormDto;
use App\Form\DtoFactory\Contract\DistributionContractWorkFormDtoFactory;
use App\Form\Handler\Contract\DistributionContractWorkFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/distribution-contracts/{contract}/works')]
class DistributionContractWorkController extends AbstractAppController
{
    public function __construct(
        private readonly DistributionContractWorkFormDtoFactory $distributionContractWorkFormDtoFactory,
        private readonly DistributionContractWorkFormHandler $distributionContractWorkFormHandler,
    ) {}

    #[Route('/add', name: 'app_distribution_contract_work_add')]
    public function add(Request $request, DistributionContract $contract): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, $contract, null);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var WorkFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_work_show', ['id' => $dto->getWork()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Add new work for distribution contract', [], 'contract'),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: 'app_distribution_contract_work_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, DistributionContractWork $distributionContractWork): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, $distributionContractWork->getDistributionContract(), $distributionContractWork);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_distribution_contract_show', ['id' => $distributionContractWork->getDistributionContract()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update work %name% for distribution contract', ['%name%' => $distributionContractWork->getWork()->getName()], 'contract'),
            'form' => $form,
        ]);
    }

    private function getFormHandlerResponse(Request $request, DistributionContract $distributionContract, ?DistributionContractWork $distributionContractWork): FormHandlerResponseInterface
    {
        $dto = $this->distributionContractWorkFormDtoFactory->create($distributionContract, $distributionContractWork);

        return $this->formHandlerManager->createAndHandle(
            $this->distributionContractWorkFormHandler,
            $request,
            $dto
        );
    }
}
