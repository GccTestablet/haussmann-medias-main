<?php

declare(strict_types=1);

namespace App\Service\Setting;

use App\Entity\Setting\BroadcastChannel;
use App\Enum\Pager\ColumnEnum;
use App\Repository\Broadcast\BroadcastChannelRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class BroadcastChannelManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    /**
     * @param Collection<BroadcastChannel>|null $includeBroadcastChannels
     *
     * @return BroadcastChannel[]
     */
    public function findAll(Collection $includeBroadcastChannels = null): array
    {
        $broadcastChannels = $this->getRepository()
            ->getPagerQueryBuilder([], [ColumnEnum::NAME => 'ASC'], null)
            ->getQuery()
            ->getResult()
        ;

        if (!$includeBroadcastChannels) {
            return $broadcastChannels;
        }

        return \array_filter(
            $broadcastChannels,
            static fn (BroadcastChannel $broadcastChannel) => !$broadcastChannel->isArchived() || $includeBroadcastChannels->contains($broadcastChannel)
        );
    }

    public function findOneByName(string $name): ?BroadcastChannel
    {
        return $this->getRepository()->findOneBy(['name' => $name]);
    }

    public function findOneBySlug(string $slug): ?BroadcastChannel
    {
        return $this->getRepository()->findOneBy(['slug' => $slug]);
    }

    /**
     * @return BroadcastChannelRepository|EntityRepository<BroadcastChannel>
     */
    private function getRepository(): BroadcastChannelRepository|EntityRepository
    {
        return $this->entityManager->getRepository(BroadcastChannel::class);
    }
}
