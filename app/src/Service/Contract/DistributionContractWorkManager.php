<?php

declare(strict_types=1);

namespace App\Service\Contract;

use App\Entity\Contract\DistributionContract;
use App\Entity\Contract\DistributionContractWork;
use App\Entity\Work;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class DistributionContractWorkManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function findOrCreateByDistributionContractAndWork(DistributionContract $distributionContract, Work $work): DistributionContractWork
    {
        $contractWork = $this->findOneByDistributionContractAndWork($distributionContract, $work);
        if ($contractWork) {
            return $contractWork;
        }

        return (new DistributionContractWork())
            ->setDistributionContract($distributionContract)
            ->setWork($work)
        ;
    }

    public function findOneByDistributionContractAndWork(DistributionContract $distributionContract, Work $work): ?DistributionContractWork
    {
        return $this->getRepository()->findOneBy([
            'distributionContract' => $distributionContract,
            'work' => $work,
        ]);
    }

    /**
     * @return DistributionContractWork[]
     */
    public function findByDistributionContract(DistributionContract $distributionContract): array
    {
        return $this->getRepository()->findBy([
            'distributionContract' => $distributionContract,
        ]);
    }

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(DistributionContractWork::class);
    }
}
