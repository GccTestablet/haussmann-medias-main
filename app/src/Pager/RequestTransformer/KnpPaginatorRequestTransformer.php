<?php

declare(strict_types=1);

namespace App\Pager\RequestTransformer;

use App\Model\Pager\FilterCollection;
use App\Pager\Shared\PagerInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class KnpPaginatorRequestTransformer extends AbstractRequestTransformer
{
    public function __construct(
        private readonly PagerInterface $pager
    ) {}

    public function createFromRequest(Request $request, FilterCollection $filterCollection): void
    {
        foreach ($filterCollection->getFilters() as $filter) {
            $this->addFilteredBy([$filter->getColumn()->value => $filter->getValue()]);
        }

        $form = $this->pager->getForm();
        $form->handleRequest($request);

        if (\is_array($form->getData())) {
            foreach ($form->getData() as $key => $value) {
                if (!$value || ($value instanceof Collection && 0 === $value->count())) {
                    continue;
                }

                $this->addFilteredBy([$key => $value]);
                $this->isFiltered = true;
            }

            $this->pager->setForm($form);
        }

        if (\count($this->pager->getSort()) > 1) {
            throw new BadRequestHttpException('Default sort cannot be more than one.');
        }

        $defaultSort = $this->pager->getSort();

        $sort = \key($defaultSort);
        if ($request->query->has('sort') && !empty($request->query->getAlnum('sort'))) {
            $sort = $request->query->getAlnum('sort');
        } else {
            $request->query->set('sort', $sort);
        }

        $direction = \current($defaultSort);
        if ($request->query->has('direction') && !empty($request->query->getAlnum('direction'))) {
            $direction = $request->query->getAlnum('direction');
        } else {
            $request->query->set('direction', $direction);
        }

        $this->orderedBy[$sort] = $direction;

        if ($request->query->has('page') && !empty($request->query->getInt('page'))) {
            $this->page = $request->query->getInt('page', $this->page);
        } else {
            $request->query->set('page', $this->page);
        }

        if ($request->query->has('limit')) {
            $this->limit = $request->query->getInt('limit', $this->limit);
        } else {
            $this->limit = $this->pager->getLimit();
        }
    }
}
