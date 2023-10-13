<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Setting;

use App\Entity\Setting\BroadcastChannel;
use App\Entity\Setting\BroadcastService;
use App\Form\Dto\Setting\BroadcastServiceFormDto;
use App\Tools\Parser\ObjectParser;

class BroadcastServiceFormDtoFactory
{
    public function __construct(
        private readonly ObjectParser $objectParser
    ) {}

    public function create(BroadcastChannel $channel, ?BroadcastService $service): BroadcastServiceFormDto
    {
        if (!$service) {
            $service = (new BroadcastService())
                ->setBroadcastChannel($channel)
            ;

            return new BroadcastServiceFormDto($service, false);
        }

        $dto = new BroadcastServiceFormDto($service, true);
        $this->objectParser->mergeFromObject($service, $dto);

        return $dto;
    }

    public function updateEntity(BroadcastServiceFormDto $dto, BroadcastService $service): void
    {
        $this->objectParser->mergeFromObject($dto, $service);
    }
}
