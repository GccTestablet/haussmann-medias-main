<?php

namespace App\Pager;

use App\Pager\Shared\PagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class PagerManager
{
    public function __construct(
        private readonly PaginatorInterface $paginator
    ) {}

    public function create(PagerInterface $pager, Request $request): PagerResponse
    {
        $pager->init($request);
    }
}