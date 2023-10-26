<?php

declare(strict_types=1);

namespace App\Model\Pager\Field;

use App\Enum\Pager\FieldTypeEnum;

interface FieldInterface
{
    public function getType(): FieldTypeEnum;
}
