<?php

declare(strict_types=1);

namespace App\Pager\Work;

use App\Entity\Work\WorkTerritory;
use App\Enum\Pager\ColumnEnum;
use App\Model\Pager\Column;
use App\Model\Pager\ColumnHeader;
use App\Model\Pager\Field\BooleanField;
use App\Pager\Shared\AbstractPager;
use App\Repository\Work\WorkTerritoryRepository;
use Doctrine\ORM\EntityRepository;

class WorkTerritoryPager extends AbstractPager
{
    protected static ?string $pagerId = 'app-pager-work-territory';
    protected static array $defaultSort = [ColumnEnum::TERRITORY->value => 'ASC'];

    protected static array $columns = [
        ColumnEnum::TERRITORY,
        ColumnEnum::EXCLUSIVE,
        ColumnEnum::CHANNELS,
    ];

    public function buildQuery(array $criteria = [], array $orderBy = [], int $limit = self::DEFAULT_LIMIT, int $offset = self::DEFAULT_OFFSET): void
    {
        $this->query = $this->getRepository()->getPagerQueryBuilder($criteria, $orderBy, $limit, $offset);
    }

    protected function configureColumnSchema(): void
    {
        self::$columnSchema = [
            new Column(
                id: ColumnEnum::TERRITORY,
                header: new ColumnHeader(
                    callback: fn () => 'Territory',
                ),
                callback: fn (WorkTerritory $workTerritory) => $workTerritory->getTerritory()->getName(),
            ),
            new Column(
                id: ColumnEnum::EXCLUSIVE,
                header: new ColumnHeader(
                    callback: fn () => 'Exclusive',
                ),
                callback: fn (WorkTerritory $workTerritory) => new BooleanField($workTerritory->isExclusive()),
            ),
            new Column(
                id: ColumnEnum::CHANNELS,
                header: new ColumnHeader(
                    callback: fn () => 'Channels',
                    sortable: false,
                ),
                callback: fn (WorkTerritory $workTerritory) => \implode(', ', $workTerritory
                    ->getBroadcastChannels()
                    ->map(fn ($channel) => $channel->getName())
                    ->toArray()
                ),
            ),
        ];
    }

    /**
     * @return WorkTerritoryRepository|EntityRepository<WorkTerritory>
     */
    private function getRepository(): WorkTerritoryRepository|EntityRepository
    {
        return $this->entityManager->getRepository(WorkTerritory::class);
    }
}
