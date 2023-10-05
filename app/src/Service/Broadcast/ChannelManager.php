<?php

declare(strict_types=1);

namespace App\Service\Broadcast;

use App\Entity\BroadcastChannel;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class ChannelManager
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

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(BroadcastChannel::class);
    }
}
