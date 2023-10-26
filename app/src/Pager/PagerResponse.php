<?php

declare(strict_types=1);

namespace App\Pager;

use App\Pager\Shared\PagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

class PagerResponse
{
    public function __construct(
        private readonly PagerInterface $pager,
        /**
         * @var PaginationInterface<object>
         */
        private readonly PaginationInterface $pagination
    ) {}

    public function getPager(): PagerInterface
    {
        return $this->pager;
    }

    /**
     * @return PaginationInterface<object>
     */
    public function getPagination(): PaginationInterface
    {
        return $this->pagination;
    }
}
