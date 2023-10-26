<?php

declare(strict_types=1);

namespace App\Pager;

use App\Model\Pager\Filter;
use App\Pager\RequestTransformer\KnpPaginatorRequestTransformer;
use App\Pager\Shared\PagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class PagerManager
{
    public function __construct(
        private readonly PaginatorInterface $paginator
    ) {}

    /**
     * @param Filter[] $filters
     */
    public function create(PagerInterface $pager, Request $request, array $filters = []): PagerResponse
    {
        $pager->init($request);

        $requestTransformer = new KnpPaginatorRequestTransformer($pager);
        $requestTransformer->createFromRequest($request, $filters);

        $page = $requestTransformer->getPage();
        $limit = $requestTransformer->getLimit();
        $offset = ($page - 1) * $limit;

        $pager->buildQuery(
            $requestTransformer->getFilteredBy(),
            $requestTransformer->getOrderedBy(),
            $limit,
            $offset
        );

        $sort = $requestTransformer->getOrderedBy();

        $pagination = $this->paginator->paginate([], 1, $limit, [
            PaginatorInterface::DEFAULT_SORT_FIELD_NAME => \key($sort),
            PaginatorInterface::DEFAULT_SORT_DIRECTION => \current($sort),
        ]);

        $pagination->setItems($pager->getItems());
        $pagination->setCurrentPageNumber($page);
        $pagination->setTotalItemCount($pager->getItemsCount());
        $pagination->setCustomParameters([
            'size' => 'small',
        ]);

        return new PagerResponse(
            $pager,
            $pagination
        );
    }
}
