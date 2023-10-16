<?php

declare(strict_types=1);

namespace App\Service\Setting;

use App\Entity\Setting\BroadcastChannel;
use App\Entity\Work;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class BroadcastChannelManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    /**
     * @return BroadcastChannel[]
     */
    public function findAll(): array
    {
        return $this->getRepository()->findBy([], ['name' => 'ASC']);
    }

    public function findByWork(Work $work): array
    {
        return $this->getRepository()->findBy([
            'works' => $work,
        ]);
    }

    public function findOneByName(string $name): ?BroadcastChannel
    {
        return $this->getRepository()->findOneBy(['name' => $name]);
    }

    public function findOneBySlug(string $slug): ?BroadcastChannel
    {
        return $this->getRepository()->findOneBy(['slug' => $slug]);
    }

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(BroadcastChannel::class);
    }
}
