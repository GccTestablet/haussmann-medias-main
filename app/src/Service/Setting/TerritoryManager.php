<?php

declare(strict_types=1);

namespace App\Service\Setting;

use App\Entity\Setting\Territory;
use App\Enum\Pager\ColumnEnum;
use App\Repository\Setting\TerritoryRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class TerritoryManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    /**
     * @param Collection<Territory>|null $includeTerritories
     *
     * @return Territory[]
     */
    public function findAll(Collection $includeTerritories = null): array
    {
        $territories = $this->getRepository()
            ->getPagerQueryBuilder([], [ColumnEnum::NAME => 'ASC'], null)
            ->getQuery()
            ->getResult()
        ;

        if (!$includeTerritories) {
            return $territories;
        }

        return \array_filter($territories, static fn (Territory $territory) => !$territory->isArchived() || $includeTerritories->contains($territory));
    }

    /**
     * @return TerritoryRepository|EntityRepository<Territory>
     */
    private function getRepository(): TerritoryRepository|EntityRepository
    {
        return $this->entityManager->getRepository(Territory::class);
    }
}
