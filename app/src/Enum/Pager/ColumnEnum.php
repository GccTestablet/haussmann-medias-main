<?php

declare(strict_types=1);

namespace App\Enum\Pager;

use App\Enum\Shared\ConstEnumTrait;

class ColumnEnum
{
    use ConstEnumTrait;

    final public const EXTRA = 'extra';
    final public const SORT = 'sort';
    final public const DIRECTION = 'direction';
    final public const PAGE = 'page';

    // Misc
    final public const ARCHIVED = 'archived';
    final public const INCLUDE = 'include';
    final public const ID = 'id';
    final public const NAME = 'name';
    final public const TYPE = 'type';
    final public const COMMENT = 'comment';
    final public const STARTS_AT = 'startsAt';
    final public const ENDS_AT = 'endsAt';
    final public const AMOUNT = 'amount';
    final public const PERIOD = 'period';
    final public const CREATED_AT = 'createdAt';
    final public const CREATED_BY = 'createdBy';
    final public const UPDATED_AT = 'updatedAt';
    final public const UPDATED_BY = 'updatedBy';
    final public const ACTIONS = 'actions';

    // Work
    final public const INTERNAL_ID = 'internalId';
    final public const IMDB_ID = 'imdbId';
    final public const CONTRACT = 'contract';
    final public const COMPANY = 'company';
    final public const QUOTAS = 'quotas';
    final public const COUNTRY = 'country';
    final public const COUNTRIES = 'countries';
    final public const EXCLUSIVE = 'exclusive';
    final public const PERCENT_REVERSION = 'percentReversion';

    // Contract
    final public const SIGNED_AT = 'signedAt';
    final public const ACQUISITION_CONTRACT = 'acquisitionContract';
    final public const ACQUISITION_CONTRACT_NAME = 'acquisitionContractName';
    final public const DISTRIBUTION_CONTRACT = 'distributionContract';
    final public const BENEFICIARY = 'beneficiary';
    final public const BENEFICIARIES = 'beneficiaries';
    final public const SELLER = 'seller';
    final public const ACQUIRER = 'acquirer';
    final public const DISTRIBUTOR = 'distributor';
    final public const DISTRIBUTORS = 'distributors';
    final public const WORK = 'work';
    final public const WORKS = 'works';
    final public const WORKS_COUNT = 'worksCount';

    // Settings
    final public const CHANNEL = 'channel';
    final public const CHANNELS = 'channels';
    final public const EXCLUDE_CHANNELS = 'excludeChannels';
    final public const TERRITORY = 'territory';
    final public const TERRITORIES = 'territories';
}
