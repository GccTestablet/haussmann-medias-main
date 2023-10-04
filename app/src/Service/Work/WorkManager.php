<?php

declare(strict_types=1);

namespace App\Service\Work;

use App\Entity\Work;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

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

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Work::class);
    }
}
