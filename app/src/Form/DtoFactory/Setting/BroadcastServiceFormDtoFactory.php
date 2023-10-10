<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Setting;

use App\Entity\Setting\BroadcastChannel;
use App\Entity\Setting\BroadcastService;
use App\Form\Dto\Setting\BroadcastServiceFormDto;

class BroadcastServiceFormDtoFactory
{
    public function create(BroadcastChannel $channel, ?BroadcastService $service): BroadcastServiceFormDto
    {
        if (!$service) {
            $service = (new BroadcastService())
                ->setBroadcastChannel($channel)
            ;

            return new BroadcastServiceFormDto($service, false);
        }

        return (new BroadcastServiceFormDto($service, true))
            ->setName($service->getName())
        ;
    }

    public function updateEntity(BroadcastServiceFormDto $dto, BroadcastService $service): void
    {
        $service
            ->setName($dto->getName())
        ;
    }
}
