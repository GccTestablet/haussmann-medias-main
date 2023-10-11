<?php

declare(strict_types=1);

namespace App\Model\Layout;

class SearchBarResult
{
    public function __construct(
        private readonly string $label,
        private string $url
    ) {}

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }
}
