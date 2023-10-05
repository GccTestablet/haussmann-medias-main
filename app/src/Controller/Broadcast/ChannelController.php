<?php

declare(strict_types=1);

namespace App\Controller\Broadcast;

use App\Controller\Shared\AbstractAppController;
use App\Entity\BroadcastChannel;
use App\Form\Dto\Broadcast\ChannelFormDto;
use App\Form\DtoFactory\Broadcast\ChannelFormDtoFactory;
use App\Form\Handler\Broadcast\ChannelFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Service\Broadcast\ChannelManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

#[Route('/broadcast/channels')]
class ChannelController extends AbstractAppController
{
    public function __construct(
        private readonly ChannelFormDtoFactory $channelFormDtoFactory,
        private readonly ChannelFormHandler $channelFormHandler,
        private readonly ChannelManager $channelManager
    ) {}

    #[Route(name: 'app_broadcast_channel_index')]
    public function index(): Response
    {
        return $this->render('broadcast/channel/index.html.twig', [
            'channels' => $this->channelManager->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_broadcast_channel_show', requirements: ['id' => '\d+'])]
    public function show(BroadcastChannel $channel): Response
    {
        return $this->render('broadcast/channel/show.html.twig', [
            'channel' => $channel,
        ]);
    }

    #[Route('/add', name: 'app_broadcast_channel_add')]
    public function add(Request $request): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, null);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var ChannelFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_broadcast_channel_show', ['id' => $dto->getChannel()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Add broadcast channel', [], 'broadcast_channel'),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: 'app_broadcast_channel_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, BroadcastChannel $channel): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, $channel);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var ChannelFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_broadcast_channel_show', ['id' => $dto->getChannel()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update broadcast channel %name%', ['%name%' => $channel->getName()], 'broadcast_channel'),
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
