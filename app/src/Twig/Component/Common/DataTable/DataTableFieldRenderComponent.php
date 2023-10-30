<?php

declare(strict_types=1);

namespace App\Twig\Component\Common\DataTable;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'data_table_field_render', template: 'component/common/data_table/data_table_field_render.html.twig')]
class DataTableFieldRenderComponent
{
    public mixed $viewData;
}
