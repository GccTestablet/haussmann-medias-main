<?php

declare(strict_types=1);

namespace App\Service\Setting;

use App\Entity\Setting\AdaptationCostType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class AdaptationCostTypeManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    /**
     * @return AdaptationCostType[]
     */
    public function findAll(): array
    {
        return $this->getRepository()->findBy([], ['name' => 'ASC']);
    }

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(AdaptationCostType::class);
    }
}
