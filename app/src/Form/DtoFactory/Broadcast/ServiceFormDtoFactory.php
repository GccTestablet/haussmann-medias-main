<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Broadcast;

use App\Entity\BroadcastChannel;
use App\Entity\BroadcastService;
use App\Form\Dto\Broadcast\ServiceFormDto;

class ServiceFormDtoFactory
{
    public function create(BroadcastChannel $channel, ?BroadcastService $service): ServiceFormDto
    {
        if (!$service) {
            $service = (new BroadcastService())
                ->setBroadcastChannel($channel)
            ;

            return new ServiceFormDto($service, false);
        }

        return (new ServiceFormDto($service, true))
            ->setName($service->getName())
        ;
    }

    public function updateEntity(ServiceFormDto $dto, BroadcastService $service): void
    {
        $service
            ->setName($dto->getName())
        ;
    }
}
