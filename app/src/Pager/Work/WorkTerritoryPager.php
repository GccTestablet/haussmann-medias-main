<?php

declare(strict_types=1);

namespace App\Pager\Work;

use App\Entity\Work\WorkTerritory;
use App\Enum\Pager\ColumnEnum;
use App\Model\Pager\Column;
use App\Model\Pager\ColumnHeader;
use App\Model\Pager\Field\BooleanField;
use App\Model\Pager\Field\IconField;
use App\Pager\Shared\AbstractPager;
use App\Repository\Work\WorkTerritoryRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Translation\TranslatableMessage;

class WorkTerritoryPager extends AbstractPager
{
    protected static ?string $pagerId = 'app-pager-work-territory';
    protected static array $defaultSort = [ColumnEnum::TERRITORY => 'ASC'];

    protected static array $columns = [
        ColumnEnum::EXTRA,
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
                id: ColumnEnum::EXTRA,
                header: new ColumnHeader(
                    callback: fn () => '',
                    sortable: false,
                ),
                callback: fn (WorkTerritory $workTerritory) => $workTerritory->getTerritory()->isArchived() ? new IconField(
                    icon: 'fas fa-archive',
                    attributes: [
                        'title' => new TranslatableMessage('Archived', [], 'misc'),
                        'class' => 'text-warning',
                    ]
                ) : null,
            ),
            new Column(
                id: ColumnEnum::TERRITORY,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Territory', [], 'setting')
                ),
                callback: fn (WorkTerritory $workTerritory) => $workTerritory->getTerritory()->getName(),
            ),
            new Column(
                id: ColumnEnum::EXCLUSIVE,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Exclusive', [], 'work'),
                ),
                callback: fn (WorkTerritory $workTerritory) => new BooleanField($workTerritory->isExclusive()),
            ),
            new Column(
                id: ColumnEnum::CHANNELS,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Channels', [], 'setting'),
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
