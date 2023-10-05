<?php

declare(strict_types=1);

namespace App\Form\Dto\Broadcast;

use App\Entity\BroadcastChannel;
use Symfony\Component\Validator\Constraints as Assert;

class ChannelFormDto
{
    #[Assert\NotBlank]
    private ?string $name = null;

    public function __construct(
        private readonly BroadcastChannel $channel,
        private readonly bool $exists,
    ) {}

    public function getChannel(): BroadcastChannel
    {
        return $this->channel;
    }

    public function isExists(): bool
    {
        return $this->exists;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
