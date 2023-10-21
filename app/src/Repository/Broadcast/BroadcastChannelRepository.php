<?php

declare(strict_types=1);

namespace App\Repository\Broadcast;

use App\Entity\Setting\BroadcastChannel;
use App\Entity\Work\Work;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class BroadcastChannelRepository extends EntityRepository
{
    public function getAvailableChannelByWorkQueryBuilder(Work $work, ?BroadcastChannel $channel): QueryBuilder
    {
        $channels = [];
        foreach ($work->getWorkReversions() as $reversion) {
            if ($channel === $reversion->getChannel()) {
                continue;
            }

            $channels[] = $reversion->getChannel();
        }

        $queryBuilder = $this->createQueryBuilder('bc');
        if (0 === \count($channels)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where('bc.id NOT IN (:channels)')
            ->setParameter('channels', $channels)
        ;
    }
}
