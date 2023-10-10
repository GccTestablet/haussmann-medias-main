<?php

declare(strict_types=1);

namespace App\Controller\Setting;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Setting\BroadcastService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/settings/broadcast/services')]
class BroadcastServiceController extends AbstractAppController
{
    #[Route('/{id}', name: 'app_setting_broadcast_service_show', requirements: ['id' => '\d+'])]
    public function show(BroadcastService $service): Response
    {
        return $this->render('setting/broadcast/service/show.html.twig', [
            'service' => $service,
        ]);
    }
}
