<?php

declare(strict_types=1);

namespace App\Service\Contract;

use App\Entity\AcquisitionContract;
use App\Repository\Contract\AcquisitionContractRepository;
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
     * @return AcquisitionContractRepository|EntityRepository<AcquisitionContract>
     */
    private function getRepository(): AcquisitionContractRepository|EntityRepository
    {
        return $this->entityManager->getRepository(AcquisitionContract::class);
    }
}
