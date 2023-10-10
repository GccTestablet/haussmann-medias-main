<?php

declare(strict_types=1);

namespace App\Controller\Setting;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Setting\BroadcastChannel;
use App\Entity\Setting\BroadcastService;
use App\Form\Dto\Setting\BroadcastServiceFormDto;
use App\Form\DtoFactory\Setting\BroadcastServiceFormDtoFactory;
use App\Form\Handler\Setting\BroadcastServiceFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

#[Route('/settings/broadcast/channels/{channel}/services')]
class BroadcastChannelServiceController extends AbstractAppController
{
    public function __construct(
        private readonly BroadcastServiceFormDtoFactory $serviceFormDtoFactory,
        private readonly BroadcastServiceFormHandler $serviceFormHandler,
    ) {}

    #[Route('/add', name: 'app_setting_broadcast_channel_service_add')]
    public function add(Request $request, BroadcastChannel $channel): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, null, $channel);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var BroadcastServiceFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_setting_broadcast_channel_show', ['id' => $dto->getService()->getBroadcastChannel()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Add broadcast service to channel %channel%', [
                '%channel%' => $channel->getName(),
            ], 'setting'),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: 'app_setting_broadcast_channel_service_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, BroadcastService $service): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, $service, $service->getBroadcastChannel());

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var BroadcastServiceFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_setting_broadcast_channel_show', ['id' => $dto->getService()->getBroadcastChannel()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update broadcast service %service% from %channel%', [
                '%service%' => $service->getName(),
                '%channel%' => $service->getBroadcastChannel()->getName(),
            ], 'setting'),
            'form' => $form,
        ]);
    }

    private function getFormHandlerResponse(Request $request, ?BroadcastService $service, BroadcastChannel $channel): FormHandlerResponseInterface
    {
        $dto = $this->serviceFormDtoFactory->create($channel, $service);

        return $this->formHandlerManager->createAndHandle(
            $this->serviceFormHandler,
            $request,
            $dto
        );
    }
}
