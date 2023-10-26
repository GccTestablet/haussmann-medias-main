<?php

namespace App\Pager\Shared;

use Symfony\Component\HttpFoundation\Request;

interface PagerInterface
{
    final public const DEFAULT_LIMIT = 25;
    final public const DEFAULT_OFFSET = 0;

    public function init(Request $request): void;

    public function buildQuery(array $criteria = [], array $orderBy = [], int $limit = self::DEFAULT_LIMIT, int $offset = self::DEFAULT_OFFSET): void;
}