<?php

declare(strict_types=1);

namespace App\Controller\Setting;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Setting\BroadcastChannel;
use App\Form\Dto\Setting\BroadcastChannelFormDto;
use App\Form\DtoFactory\Setting\BroadcastChannelFormDtoFactory;
use App\Form\Handler\Setting\BroadcastChannelFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Service\Setting\BroadcastChannelManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

#[Route('/settings/broadcast/channels')]
class BroadcastChannelController extends AbstractAppController
{
    public function __construct(
        private readonly BroadcastChannelFormDtoFactory $channelFormDtoFactory,
        private readonly BroadcastChannelFormHandler $channelFormHandler,
        private readonly BroadcastChannelManager $broadcastChannelManager
    ) {}

    #[Route(name: 'app_setting_broadcast_channel_index')]
    public function index(): Response
    {
        return $this->render('setting/broadcast/channel/index.html.twig', [
            'channels' => $this->broadcastChannelManager->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_setting_broadcast_channel_show', requirements: ['id' => '\d+'])]
    public function show(BroadcastChannel $channel): Response
    {
        return $this->render('setting/broadcast/channel/show.html.twig', [
            'channel' => $channel,
        ]);
    }

    #[Route('/add', name: 'app_setting_broadcast_channel_add')]
    public function add(Request $request): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, null);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var BroadcastChannelFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_setting_broadcast_channel_show', ['id' => $dto->getChannel()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Add broadcast channel', [], 'setting'),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: 'app_setting_broadcast_channel_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, BroadcastChannel $channel): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, $channel);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var BroadcastChannelFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_setting_broadcast_channel_show', ['id' => $dto->getChannel()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update broadcast channel %name%', ['%name%' => $channel->getName()], 'setting'),
            'form' => $form,
        ]);
    }

    private function getFormHandlerResponse(Request $request, ?BroadcastChannel $channel): FormHandlerResponseInterface
    {
        $dto = $this->channelFormDtoFactory->create($channel);

        return $this->formHandlerManager->createAndHandle(
            $this->channelFormHandler,
            $request,
            $dto
        );
    }
}
