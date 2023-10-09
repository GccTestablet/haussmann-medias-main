<?php

declare(strict_types=1);

namespace App\Service\Contract;

use App\Entity\Contract;
use App\Repository\ContractRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ContractManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function findBySearchQuery(string $query, int $limit = 5): Paginator
    {
        $queryBuilder = $this->getRepository()->getQueryBuilderBySearchQuery($query, $limit);

        return new Paginator($queryBuilder);
    }

    /**
     * @return ContractRepository|EntityRepository<Contract>
     */
    private function getRepository(): ContractRepository|EntityRepository
    {
        return $this->entityManager->getRepository(Contract::class);
    }
}
