<?php

declare(strict_types=1);

namespace App\Enum\Pager;

enum ColumnEnum: string
{
    case EXTRA = 'extra';
    case SORT = 'sort';
    case DIRECTION = 'direction';
    case PAGE = 'page';

    // Misc
    case ID = 'id';
    case NAME = 'name';
    case STARTS_AT = 'startsAt';
    case ENDS_AT = 'endsAt';
    case CREATED_AT = 'createdAt';
    case CREATED_BY = 'createdBy';
    case UPDATED_AT = 'updatedAt';
    case UPDATED_BY = 'updatedBy';
    case ACTIONS = 'actions';

    // Work
    case INTERNAL_ID = 'internalId';
    case CONTRACT = 'contract';
    case COMPANY = 'company';

    // Contract
    case ACQUISITION_CONTRACT = 'acquisitionContract';
    case ACQUISITION_CONTRACT_NAME = 'acquisitionContractName';
    case BENEFICIARY = 'beneficiary';
    case BENEFICIARIES = 'beneficiaries';
    case WORKS = 'works';
    case WORKS_COUNT = 'worksCount';
}
