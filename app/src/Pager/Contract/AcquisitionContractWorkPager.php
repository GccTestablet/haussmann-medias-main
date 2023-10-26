<?php

declare(strict_types=1);

namespace App\Pager\Contract;

use App\Enum\Pager\ColumnEnum;
use App\Pager\Work\WorkPager;

class AcquisitionContractWorkPager extends WorkPager
{
    protected static array $columns = [
        ColumnEnum::EXTRA,
        ColumnEnum::INTERNAL_ID,
        ColumnEnum::NAME,
        ColumnEnum::ACTIONS,
    ];
}
