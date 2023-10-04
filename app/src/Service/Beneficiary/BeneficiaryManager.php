<?php

declare(strict_types=1);

namespace App\Service\Beneficiary;

use App\Entity\Beneficiary;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class BeneficiaryManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    /**
     * @return Beneficiary[]
     */
    public function findAll(): array
    {
        return $this->getRepository()->findBy([], ['name' => 'ASC']);
    }

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(Beneficiary::class);
    }
}
