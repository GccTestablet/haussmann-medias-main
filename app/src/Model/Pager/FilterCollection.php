<?php

declare(strict_types=1);

namespace App\Model\Pager;

use App\Enum\Pager\ColumnEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class FilterCollection
{
    private readonly Collection $filters;

    public function __construct()
    {
        $this->filters = new ArrayCollection();
    }

    public function getFilters(): Collection
    {
        return $this->filters;
    }

    public function getFilter(ColumnEnum $column): ?Filter
    {
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
