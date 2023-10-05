<?php

declare(strict_types=1);

namespace App\Controller\Contract;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Contract;
use App\Entity\Work;
use App\Form\Dto\Work\WorkFormDto;
use App\Form\DtoFactory\Work\WorkFormDtoFactory;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Handler\Work\WorkFormHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/contracts/{contract}/works')]
class ContractWorkController extends AbstractAppController
{
    public function __construct(
        private readonly WorkFormDtoFactory $workFormDtoFactory,
        private readonly WorkFormHandler $workFormHandler,
    ) {}

    #[Route('/add', name: 'app_contract_work_add')]
    public function add(Request $request, Contract $contract): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, $contract, null);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var WorkFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_work_show', ['id' => $dto->getWork()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Add work', [], 'work'),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: 'app_contract_work_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, Work $work): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, $work->getContract(), $work);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_work_show', ['id' => $work->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update work %name%', ['%name%' => $work->getName()], 'work'),
            'form' => $form,
        ]);
    }

    private function getFormHandlerResponse(Request $request, Contract $contract, ?Work $work): FormHandlerResponseInterface
    {
        $dto = $this->workFormDtoFactory->create($work, $contract);

        return $this->formHandlerManager->createAndHandle(
            $this->workFormHandler,
            $request,
            $dto
        );
    }
}
