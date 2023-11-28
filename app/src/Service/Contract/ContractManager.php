<?php

declare(strict_types=1);

namespace App\Service\Contract;

use App\Entity\Contract\AcquisitionContract;
use App\Repository\Contract\AcquisitionContractRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    private function getRepository(): AcquisitionContractRepository
    {
        return $this->entityManager->getRepository(AcquisitionContract::class);
    }
}
