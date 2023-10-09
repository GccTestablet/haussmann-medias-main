<?php

declare(strict_types=1);

namespace App\Service\Work;

use App\Entity\Company;
use App\Entity\Work;
use App\Repository\WorkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class WorkManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

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
