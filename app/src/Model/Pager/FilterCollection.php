<?php

declare(strict_types=1);

namespace App\Model\Pager;

use App\Enum\Pager\ColumnEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class FilterCollection
{
    private readonly Collection $filters;

    /**
     * @param array<Filter> $filters
     */
    public function __construct(
        array $filters = []
    ) {
        $this->filters = new ArrayCollection($filters);
    }

    public function getFilters(): Collection
    {
        return $this->filters;
    }

    public function getFilter(string $column): ?Filter
    {
        if (!ColumnEnum::isValueValid($column)) {
            throw new \InvalidArgumentException(\sprintf('Invalid column "%s" given.', $column));
        }

        foreach ($this->filters as $filter) {
            if ($filter->getColumn() === $column) {
                return $filter;
            }
        }

        return null;
    }

    public function addFilter(Filter $filter): static
    {
        if (!$this->filters->contains($filter)) {
            $this->filters->add($filter);
        }

        return $this;
    }
}
