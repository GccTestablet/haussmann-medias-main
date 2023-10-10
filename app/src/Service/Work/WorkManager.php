<?php

declare(strict_types=1);

namespace App\Service\Work;

use App\Entity\AcquisitionContract;
use App\Entity\Company;
use App\Entity\Work;
use App\Repository\WorkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

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
    public function findBySearchQuery(string $query, int $limit = 5): array
    {
        $queryBuilder = $this->getRepository()->getQueryBuilderBySearchQuery($query, $limit);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * @return WorkRepository|EntityRepository<Work>
     */
    private function getRepository(): WorkRepository|EntityRepository
    {
        return $this->entityManager->getRepository(Work::class);
    }
}
