<?php

declare(strict_types=1);

namespace App\Controller\Setting;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Setting\AdaptationCostType;
use App\Form\Dto\Setting\AdaptationCostTypeFormDto;
use App\Form\DtoFactory\Setting\AdaptationCostTypeFormDtoFactory;
use App\Form\Handler\Setting\AdaptationCostTypeFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Service\Setting\AdaptationCostTypeManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

#[Route('/settings/adaptation-cost-types')]
class AdaptationCostTypeController extends AbstractAppController
{
    public function __construct(
        private readonly AdaptationCostTypeFormDtoFactory $adaptationCostTypeFormDtoFactory,
        private readonly AdaptationCostTypeFormHandler $adaptationCostTypeFormHandler,
        private readonly AdaptationCostTypeManager $adaptationCostTypeManager
    ) {}

    #[Route(name: 'app_setting_adaptation_cost_type_index')]
    public function index(): Response
    {
        return $this->render('setting/adaptation_cost_type/index.html.twig', [
            'types' => $this->adaptationCostTypeManager->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_setting_adaptation_cost_type_show', requirements: ['id' => '\d+'])]
    public function show(AdaptationCostType $type): Response
    {
        return $this->render('setting/adaptation_cost_type/show.html.twig', [
            'type' => $type,
        ]);
    }

    #[Route('/add', name: 'app_setting_adaptation_cost_type_add')]
    public function add(Request $request): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, null);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var AdaptationCostTypeFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_setting_adaptation_cost_type_show', ['id' => $dto->getType()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Add adaptation cost type', [], 'setting'),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: 'app_setting_adaptation_cost_type_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, AdaptationCostType $type): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, $type);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_setting_adaptation_cost_type_show', ['id' => $type->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update adaptation cost type %name%', ['%name%' => $type->getName()], 'setting'),
            'form' => $form,
        ]);
    }

    private function getFormHandlerResponse(Request $request, ?AdaptationCostType $type): FormHandlerResponseInterface
    {
        $dto = $this->adaptationCostTypeFormDtoFactory->create($type);

        return $this->formHandlerManager->createAndHandle(
            $this->adaptationCostTypeFormHandler,
            $request,
            $dto
        );
    }
}
