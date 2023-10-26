<?php

declare(strict_types=1);

namespace App\Twig\Component\Common\DataTable;

use App\Model\Pager\Column;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'data_table_column_body', template: 'component/common/data_table/column_body.html.twig')]
class DataTableColumnBodyComponent
{
    public Column $column;

    public object $entity;

    /**
     * @var array<string, mixed>
     */
    public array $options = [];
}
