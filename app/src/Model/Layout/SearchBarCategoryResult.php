<?php

// src/Model/Layout/SearchBarCategoryResult.php

declare(strict_types=1);

namespace App\Model\Layout;

class SearchBarCategoryResult
{
    private array $results = [];

    public function __construct(
        private readonly string $category
    ) {}

    public function getCategory(): string
    {
        return $this->category;
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
