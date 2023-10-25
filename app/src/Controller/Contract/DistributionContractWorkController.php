<?php

declare(strict_types=1);

namespace App\Controller\Contract;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Contract\DistributionContract;
use App\Entity\Contract\DistributionContractWork;
use App\Entity\Work\Work;
use App\Form\DtoFactory\Contract\DistributionContractWorkFormDtoFactory;
use App\Form\Handler\Contract\DistributionContractWorkFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/distribution-contracts/{contract}/works', requirements: ['contract' => '\d+'])]
class DistributionContractWorkController extends AbstractAppController
{
    public function __construct(
        private readonly DistributionContractWorkFormDtoFactory $formDtoFactory,
        private readonly DistributionContractWorkFormHandler $formHandler,
    ) {}

    #[Route(path: '/add', name: 'app_distribution_contract_work_add')]
    public function add(Request $request, DistributionContract $contract): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse(
            $request,
            $contract,
            null
        );

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_distribution_contract_show', [
                'id' => $contract->getId(),
            ]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => 'Add work to distribution contract',
            'form' => $form,
        ]);
    }

    #[Route(path: '/{work}/update', name: 'app_distribution_contract_work_update', requirements: ['work' => '\d+'])]
    public function update(Request $request, DistributionContract $contract, Work $work): Response
    {
        $contractWork = $contract->getContractWork($work);
        if (!$contractWork) {
            throw $this->createNotFoundException(
                \sprintf('Work %s not found in contract %s', $work->getFrenchTitle(), $contract->getName())
            );
        }

        $formHandlerResponse = $this->getFormHandlerResponse(
            $request,
            $contract,
            $contractWork
        );

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_distribution_contract_show', [
                'id' => $contract->getId(),
            ]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update work %work% for distribution contract %contract%', [
                '%work%' => $work->getFrenchTitle(),
                '%contract%' => $contract->getName(),
            ], 'contract'),
            'form' => $form,
        ]);
    }

    private function getFormHandlerResponse(Request $request, DistributionContract $contract, ?DistributionContractWork $contractWork): FormHandlerResponseInterface
    {
        $dto = $this->formDtoFactory->create($contract, $contractWork);

        return $this->formHandlerManager->createAndHandle(
            $this->formHandler,
            $request,
            $dto
        );
    }
}
