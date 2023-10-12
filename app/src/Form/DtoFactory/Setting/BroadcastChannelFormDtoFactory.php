<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Setting;

use App\Entity\Setting\BroadcastChannel;
use App\Form\Dto\Setting\BroadcastChannelFormDto;
use App\Tools\Parser\StringParser;

class BroadcastChannelFormDtoFactory
{
    public function __construct(
        private readonly StringParser $stringParser
    ) {}

    public function create(?BroadcastChannel $channel): BroadcastChannelFormDto
    {
        if (!$channel) {
            return new BroadcastChannelFormDto(new BroadcastChannel(), false);
        }

        return (new BroadcastChannelFormDto($channel, true))
            ->setName($channel->getName())
        ;
    }

    public function updateEntity(BroadcastChannelFormDto $dto, BroadcastChannel $channel): void
    {
        $channel
            ->setName($dto->getName())
            ->setSlug($this->stringParser->slugify($dto->getName()))
        ;
    }
}
