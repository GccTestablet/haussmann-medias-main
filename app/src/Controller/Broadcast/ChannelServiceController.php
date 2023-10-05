<?php

declare(strict_types=1);

namespace App\Controller\Broadcast;

use App\Controller\Shared\AbstractAppController;
use App\Entity\BroadcastChannel;
use App\Entity\BroadcastService;
use App\Form\Dto\Broadcast\ServiceFormDto;
use App\Form\DtoFactory\Broadcast\ServiceFormDtoFactory;
use App\Form\Handler\Broadcast\ServiceFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

#[Route('/broadcast/channels/{channel}/services')]
class ChannelServiceController extends AbstractAppController
{
    public function __construct(
        private readonly ServiceFormDtoFactory $serviceFormDtoFactory,
        private readonly ServiceFormHandler $serviceFormHandler,
    ) {}

    #[Route('/add', name: 'app_broadcast_channel_service_add')]
    public function add(Request $request, BroadcastChannel $channel): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, null, $channel);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var ServiceFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_broadcast_channel_show', ['id' => $dto->getService()->getBroadcastChannel()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Add broadcast service to channel %channel%', [
                '%channel%' => $channel->getName(),
            ], 'broadcast_service'),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: 'app_broadcast_channel_service_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, BroadcastService $service): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, $service, $service->getBroadcastChannel());

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var ServiceFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_broadcast_channel_show', ['id' => $dto->getService()->getBroadcastChannel()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update broadcast service %service% from %channel%', [
                '%service%' => $service->getName(),
                '%channel%' => $service->getBroadcastChannel()->getName(),
            ], 'broadcast_service'),
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
