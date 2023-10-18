<?php

declare(strict_types=1);

namespace App\Service\Work;

use App\Entity\Company;
use App\Entity\Contract\AcquisitionContract;
use App\Entity\Contract\DistributionContract;
use App\Entity\Work\Work;
use App\Repository\WorkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class WorkManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function findNextInternalId(AcquisitionContract $contract): string
    {
        $prefix = \strtoupper(\substr($contract->getCompany()->getName(), 0, 3));

        $previousInternalId = $this->getRepository()->findLastInternalId($prefix);
        if (!$previousInternalId) {
            return \sprintf('%s%06d', $prefix, 1);
        }

        $previousId = (int) \str_replace($prefix, '', $previousInternalId);

        return \sprintf('%s%06d', $prefix, $previousId + 1);
    }

    /**
     * @return Work[]
     */
    public function findAll(): array
    {
        return $this->getRepository()->findBy([], ['name' => 'ASC']);
    }

    public function findOneByInternalId(string $internalId): ?Work
    {
        return $this->getRepository()->findOneBy(['internalId' => $internalId]);
    }

    /**
     * @return Work[]
     */
    public function findByCompany(Company $company): array
    {
        return $this->getRepository()->findByCompany($company);
    }

    /**
     * @return Work[]
     */
    public function findByDistributionContract(DistributionContract $distributionContract): array
    {
        return $this->getRepository()->findByDistributionContract($distributionContract);
    }

    public function findBySearchQuery(string $query, int $limit = 5): Paginator
    {
        $queryBuilder = $this->getRepository()->getQueryBuilderBySearchQuery($query, $limit);

        return new Paginator($queryBuilder);
    }

    /**
     * @return WorkRepository|EntityRepository<Work>
     */
    private function getRepository(): WorkRepository|EntityRepository
    {
        return $this->entityManager->getRepository(Work::class);
    }
}
