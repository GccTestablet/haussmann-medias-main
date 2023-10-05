<?php

declare(strict_types=1);

namespace App\Controller\Broadcast;

use App\Controller\Shared\AbstractAppController;
use App\Entity\BroadcastService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/broadcast/services')]
class ServiceController extends AbstractAppController
{
    #[Route('/{id}', name: 'app_broadcast_service_show', requirements: ['id' => '\d+'])]
    public function show(BroadcastService $service): Response
    {
        return $this->render('broadcast/service/show.html.twig', [
            'service' => $service,
        ]);
    }
}
