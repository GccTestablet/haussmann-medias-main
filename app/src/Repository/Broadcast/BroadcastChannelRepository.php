<?php

declare(strict_types=1);

namespace App\Repository\Broadcast;

use App\Entity\Work\Work;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use function count;

class BroadcastChannelRepository extends EntityRepository
{
    public function getAvailableChannelByWorkQueryBuilder(Work $work): QueryBuilder
    {
        $channels = [];
        foreach ($work->getWorkReversions() as $reversion) {
            $channels[] = $reversion->getChannel();
        }

        $queryBuilder = $this->createQueryBuilder('bc');
        if (0 === count($channels)) {
            return $queryBuilder;
        }

        return $queryBuilder
            ->where('bc.id NOT IN (:channels)')
            ->setParameter('channels', $channels)
        ;
    }
}
