<?php

declare(strict_types=1);

namespace App\Repository\Broadcast;

use App\Entity\Setting\BroadcastChannel;
use App\Entity\Work\Work;
use App\Enum\Pager\ColumnEnum;
use App\Pager\Shared\PagerInterface;
use App\Repository\Shared\PagerRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class BroadcastChannelRepository extends EntityRepository implements PagerRepositoryInterface
{
    public function getPagerQueryBuilder(array $criteria, array $orderBy, ?int $limit = PagerInterface::DEFAULT_LIMIT, int $offset = PagerInterface::DEFAULT_OFFSET): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('bc')
            ->leftJoin('bc.distributionContractWorkRevenues', 'dcwr')
            ->innerJoin('dcwr.contractWork', 'cw')
        ;

        foreach ($criteria as $field => $value) {
            $enum = ColumnEnum::tryFrom($field);

            match ($enum) {
                ColumnEnum::DISTRIBUTION_CONTRACT => $queryBuilder
                    ->andWhere('cw.distributionContract = :distributionContract')
                    ->setParameter('distributionContract', $value),
                default => null,
            };
        }

        foreach ($orderBy as $field => $direction) {
            $enum = ColumnEnum::tryFrom($field);

            match ($enum) {
                ColumnEnum::NAME => $queryBuilder->addOrderBy('bc.name', $direction),
                default => null,
            };
        }

        return $queryBuilder
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;
    }

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
