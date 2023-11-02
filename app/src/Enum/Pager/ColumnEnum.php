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
    case ARCHIVED = 'archived';
    case ID = 'id';
    case NAME = 'name';
    case TYPE = 'type';
    case COMMENT = 'comment';
    case STARTS_AT = 'startsAt';
    case ENDS_AT = 'endsAt';
    case AMOUNT = 'amount';
    case PERIOD = 'period';
    case CREATED_AT = 'createdAt';
    case CREATED_BY = 'createdBy';
    case UPDATED_AT = 'updatedAt';
    case UPDATED_BY = 'updatedBy';
    case ACTIONS = 'actions';

    // Work
    case INTERNAL_ID = 'internalId';
    case IMDB_ID = 'imdbId';
    case CONTRACT = 'contract';
    case COMPANY = 'company';
    case QUOTAS = 'quotas';
    case COUNTRY = 'country';
    case COUNTRIES = 'countries';
    case EXCLUSIVE = 'exclusive';
    case PERCENT_REVERSION = 'percentReversion';

    // Contract
    case SIGNED_AT = 'signedAt';
    case ACQUISITION_CONTRACT = 'acquisitionContract';
    case ACQUISITION_CONTRACT_NAME = 'acquisitionContractName';
    case DISTRIBUTION_CONTRACT = 'distributionContract';
    case BENEFICIARY = 'beneficiary';
    case BENEFICIARIES = 'beneficiaries';
    case SELLER = 'seller';
    case ACQUIRER = 'acquirer';
    case DISTRIBUTOR = 'distributor';
    case DISTRIBUTORS = 'distributors';
    case WORK = 'work';
    case WORKS = 'works';
    case WORKS_COUNT = 'worksCount';

    // Settings
    case CHANNEL = 'channel';
    case CHANNELS = 'channels';
    case EXCLUDE_CHANNELS = 'excludeChannels';
    case TERRITORY = 'territory';
    case TERRITORIES = 'territories';
}
