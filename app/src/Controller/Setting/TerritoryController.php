<?php

declare(strict_types=1);

namespace App\Controller\Setting;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Setting\Territory;
use App\Form\Dto\Setting\TerritoryFormDto;
use App\Form\DtoFactory\Setting\TerritoryFormDtoFactory;
use App\Form\Handler\Setting\TerritoryFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Service\Setting\TerritoryManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

#[Route('/settings/territories')]
class TerritoryController extends AbstractAppController
{
    public function __construct(
        private readonly TerritoryFormDtoFactory $territoryFormDtoFactory,
        private readonly TerritoryFormHandler $territoryFormHandler,
        private readonly TerritoryManager $territoryManager
    ) {}

    #[Route(name: 'app_setting_territory_index')]
    public function index(): Response
    {
        return $this->render('setting/territory/index.html.twig', [
            'territories' => $this->territoryManager->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_setting_territory_show', requirements: ['id' => '\d+'])]
    public function show(Territory $territory): Response
    {
        return $this->render('setting/territory/show.html.twig', [
            'territory' => $territory,
        ]);
    }

    #[Route('/add', name: 'app_setting_territory_add')]
    public function add(Request $request): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, null);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var TerritoryFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_setting_territory_show', ['id' => $dto->getTerritory()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Add territory', [], 'setting'),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: 'app_setting_territory_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, Territory $territory): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, $territory);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var TerritoryFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_setting_territory_show', ['id' => $dto->getTerritory()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update territory %name%', ['%name%' => $territory->getName()], 'setting'),
            'form' => $form,
        ]);
    }

    private function getFormHandlerResponse(Request $request, ?Territory $territory): FormHandlerResponseInterface
    {
        $dto = $this->territoryFormDtoFactory->create($territory);

        return $this->formHandlerManager->createAndHandle(
            $this->territoryFormHandler,
            $request,
            $dto
        );
    }
}
