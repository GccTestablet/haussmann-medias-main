<?php
//src/Model/Layout/SearchBarCategoryResult.php

declare(strict_types=1);

namespace App\Model\Layout\SearchBarResult;

use App\Model\Layout\SearchBarResult;

class SearchBarCategoryResult
{
    private array $results = [];

    public function __construct(private string $category){
    }

    public function getCategory(): string {
    return $this->category;
    }

    public function getResults(): array {
    return $this->results;
    }

    public function addResult(\App\Model\Layout\SearchBarResult\SearchBarResult $result): void {
    $this->results[] = $result;
    }
}
