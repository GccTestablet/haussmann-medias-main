<?php

declare(strict_types=1);

namespace App\Controller\Work;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Work\Work;
use App\Form\DtoFactory\Work\WorkTerritoryFormDtoFactory;
use App\Form\Handler\Work\WorkTerritoryFormHandler;
use App\Service\Setting\BroadcastChannelManager;
use App\Service\Setting\TerritoryManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/works/{work}/territories', requirements: ['work' => '\d+'])]
class WorkTerritoryController extends AbstractAppController
{
    public function __construct(
        private readonly WorkTerritoryFormHandler $formHandler,
        private readonly TerritoryManager $territoryManager,
        private readonly BroadcastChannelManager $broadcastChannelManager,
        private readonly WorkTerritoryFormDtoFactory $formDtoFactory
    ) {}

    #[Route(path: '/manage', name: 'app_work_territory_manage')]
    public function manage(Request $request, Work $work): Response
    {
        $dto = $this->formDtoFactory->create($work);

        $formHandlerResponse = $this->formHandlerManager->createAndHandle(
            $this->formHandler,
            $request,
            $dto
        );

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_work_show', [
                'id' => $work->getId(),
                'tab' => 'territories',
            ]);
        }

        return $this->render('work/territory/manage.html.twig', [
            'work' => $work,
            'territories' => $this->territoryManager->findAll(),
            'broadcastChannels' => $this->broadcastChannelManager->findAll(),
            'form' => $form,
        ]);
    }
}
