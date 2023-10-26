<?php

declare(strict_types=1);

namespace App\Enum\Pager;

enum FieldTypeEnum: string
{
    case LINK = 'pager_link';
    case LINKS = 'pager_links';
}
