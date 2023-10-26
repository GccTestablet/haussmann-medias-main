<?php

declare(strict_types=1);

namespace App\Pager\Shared;

use App\Model\Pager\Column;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

interface PagerInterface
{
    final public const DEFAULT_LIMIT = 25;
    final public const DEFAULT_OFFSET = 0;

    public function init(Request $request): void;

    /**
     * @param array<string, mixed> $criteria
     * @param array<string, string> $orderBy
     */
    public function buildQuery(array $criteria = [], array $orderBy = [], int $limit = self::DEFAULT_LIMIT, int $offset = self::DEFAULT_OFFSET): void;

    /**
     * @return array<Column>
     */
    public function getColumns(): array;

    /**
     * @return array<mixed>
     */
    public function getItems(): array;

    public function getItemsCount(): int;

    public function getForm(): FormInterface;

    public function setForm(FormInterface $form): static;

    /**
     * @return array<string, string>
     */
    public function getSort(): array;

    public function getLimit(): int;
}
