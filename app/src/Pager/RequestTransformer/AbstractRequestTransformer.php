<?php

declare(strict_types=1);

namespace App\Pager\RequestTransformer;

use App\Pager\Shared\PagerInterface;

class AbstractRequestTransformer
{
    protected bool $isInitialized = false;

    /**
     * @var array<string, mixed>
     */
    protected array $filteredBy = [];

    /**
     * @var array<string, string>
     */
    protected array $orderedBy = [];

    protected ?int $limit = PagerInterface::DEFAULT_LIMIT;

    protected int $page = 1;

    protected bool $isFiltered = false;

    public function isInitialized(): bool
    {
        return $this->isInitialized;
    }

    /**
     * @return array<string, mixed>
     */
    public function getFilteredBy(): array
    {
        return $this->filteredBy;
    }

    /**
     * @param array<string, mixed> $filter
     */
    public function addFilteredBy(array $filter): void
    {
        $this->filteredBy = [...$this->filteredBy, ...$filter];
    }

    /**
     * @return array<string, string>
     */
    public function getOrderedBy(): array
    {
        return $this->orderedBy;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function isFiltered(): bool
    {
        return $this->isFiltered;
    }
}
