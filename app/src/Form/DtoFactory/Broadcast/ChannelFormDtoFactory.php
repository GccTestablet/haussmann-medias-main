<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Broadcast;

use App\Entity\BroadcastChannel;
use App\Form\Dto\Broadcast\ChannelFormDto;

class ChannelFormDtoFactory
{
    public function create(?BroadcastChannel $channel): ChannelFormDto
    {
        if (!$channel) {
            return new ChannelFormDto(new BroadcastChannel(), false);
        }

        return (new ChannelFormDto($channel, true))
            ->setName($channel->getName())
        ;
    }

    public function updateEntity(ChannelFormDto $dto, BroadcastChannel $channel): void
    {
        $channel
            ->setName($dto->getName())
        ;
    }
}
