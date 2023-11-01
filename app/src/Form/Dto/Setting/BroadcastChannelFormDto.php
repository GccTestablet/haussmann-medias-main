<?php

declare(strict_types=1);

namespace App\Form\Dto\Setting;

use App\Entity\Setting\BroadcastChannel;
use Symfony\Component\Validator\Constraints as Assert;

class BroadcastChannelFormDto
{
    private bool $archived = false;

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

    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(?bool $archived): static
    {
        $this->archived = $archived;

        return $this;
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
