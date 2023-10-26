<?php

namespace App\Pager\RequestTransformer;

use App\Pager\Shared\PagerInterface;
use Symfony\Component\HttpFoundation\Request;

class KnpPaginatorRequestTransformer
{
    public function __construct(
        private readonly PagerInterface $pager
    ) {}

    public function createFromRequest(Request $request): void
    {
        // TODO
    }
}