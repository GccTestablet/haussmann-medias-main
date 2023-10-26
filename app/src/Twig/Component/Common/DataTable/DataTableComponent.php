<?php

declare(strict_types=1);

namespace App\Twig\Component\Common\DataTable;

use App\Model\Pager\Column;
use App\Pager\PagerResponse;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'data_table', template: 'component/common/data_table/data_table.html.twig')]
class DataTableComponent
{
    public PagerResponse $pagerResponse;

    /**
     * @var array<string, mixed>
     */
    public array $classes = [];

    /**
     * @var array<string, mixed>
     */
    public array $options = [];

    /**
     * @return array<Column>
     */
    public function getVisibleColumns(): array
    {
        $columns = [];
        foreach ($this->pagerResponse->getPager()->getColumns() as $column) {
            if (!$column->isVisible()) {
                continue;
            }

            $columns[] = $column;
        }

        return $columns;
    }
}
