<?php

// src/Model/Layout/SearchBarCategoryResult.php

declare(strict_types=1);

namespace App\Model\Layout;

use Symfony\Component\Translation\TranslatableMessage;

class SearchBarCategoryResult
{
    private array $results = [];

    public function __construct(
        private readonly TranslatableMessage $category,
        private readonly int $totalResults
    ) {}

    public function getCategory(): TranslatableMessage
    {
        return $this->category;
    }

    public function getTotalResults(): int
    {
        return $this->totalResults;
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function addResult(SearchBarResult $result): void
    {
        $this->results[] = $result;
    }
}
