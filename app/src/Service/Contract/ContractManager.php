<?php

declare(strict_types=1);

namespace App\Service\Contract;

use App\Entity\AcquisitionContract;
use App\Repository\AcquisitionContractRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ContractManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    /**
     * @return Contract[]
     */
    public function findBySearchQuery(string $query, int $limit = 5): array
    {
        $queryBuilder = $this->getRepository()->getQueryBuilderBySearchQuery($query, $limit);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return AcquisitionContractRepository|EntityRepository<AcquisitionContract>
     */
    private function getRepository(): AcquisitionContractRepository|EntityRepository
    {
        return $this->entityManager->getRepository(AcquisitionContract::class);
    }
}
