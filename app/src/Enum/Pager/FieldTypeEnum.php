<?php

declare(strict_types=1);

namespace App\Enum\Pager;

enum FieldTypeEnum: string
{
    case AMOUNT = 'pager_amount';
    case ICON = 'pager_icon';
    case BUTTON = 'pager_button';
    case POPOVER_BUTTON = 'pager_popover_button';
    case LINK = 'pager_link';
    case COLLECTION = 'pager_collection';
    case PERIOD = 'pager_period';
    case BOOLEAN = 'pager_boolean';
}
