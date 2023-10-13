<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Setting;

use App\Entity\Setting\BroadcastChannel;
use App\Form\Dto\Setting\BroadcastChannelFormDto;
use App\Tools\Parser\ObjectParser;
use App\Tools\Parser\StringParser;

class BroadcastChannelFormDtoFactory
{
    public function __construct(
        private readonly StringParser $stringParser,
        private readonly ObjectParser $objectParser
    ) {}

    public function create(?BroadcastChannel $channel): BroadcastChannelFormDto
    {
        if (!$channel) {
            return new BroadcastChannelFormDto(new BroadcastChannel(), false);
        }

        $dto = new BroadcastChannelFormDto($channel, true);
        $this->objectParser->mergeFromObject($channel, $dto);

        return $dto;
    }

    public function updateEntity(BroadcastChannelFormDto $dto, BroadcastChannel $channel): void
    {
        $this->objectParser->mergeFromObject($dto, $channel);

        $channel->setSlug($this->stringParser->slugify($dto->getName()));
    }
}
