<?php

declare(strict_types=1);

namespace App\Twig\Component\Common\DataTable;

use App\Model\Pager\Column;
use App\Pager\Shared\PagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'data_table_column_header', template: 'component/common/data_table/column_header.html.twig')]
class DataTableColumnHeaderComponent
{
    public PagerInterface $pager;

    /**
     * @var PaginationInterface<object>
     */
    public PaginationInterface $pagination;

    public Column $column;

    /**
     * @var array<string, mixed>
     */
    public array $options = [];
}
