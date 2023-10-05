<?php

declare(strict_types=1);

namespace App\Form\Dto\Broadcast;

use App\Entity\BroadcastService;
use Symfony\Component\Validator\Constraints as Assert;

class ServiceFormDto
{
    #[Assert\NotBlank]
    private ?string $name = null;

    public function __construct(
        private readonly BroadcastService $service,
        private readonly bool $exists,
    ) {}

    public function getService(): BroadcastService
    {
        return $this->service;
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
