<?php

declare(strict_types=1);

namespace App\Pager\Work;

use App\Entity\Work\WorkReversion;
use App\Enum\Pager\ColumnEnum;
use App\Model\Pager\Column;
use App\Model\Pager\ColumnHeader;
use App\Pager\Shared\AbstractPager;
use App\Repository\Work\WorkReversionRepository;
use Symfony\Component\Translation\TranslatableMessage;

class WorkReversionPager extends AbstractPager
{
    protected static ?string $pagerId = 'app-pager-work-reversion';
    protected static array $defaultSort = [ColumnEnum::TYPE => 'ASC'];

    protected static array $columns = [
        ColumnEnum::CHANNEL,
        ColumnEnum::PERCENT_REVERSION,
    ];

    public function buildQuery(array $criteria = [], array $orderBy = [], int $limit = self::DEFAULT_LIMIT, int $offset = self::DEFAULT_OFFSET): void
    {
        $this->query = $this->getRepository()->getPagerQueryBuilder($criteria, $orderBy, $limit, $offset);
    }

    protected function configureColumnSchema(): void
    {
        self::$columnSchema = [
            new Column(
                id: ColumnEnum::CHANNEL,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Channel', [], 'setting'),
                ),
                callback: fn (WorkReversion $workReversion) => $workReversion->getChannel()->getName(),
            ),
            new Column(
                id: ColumnEnum::PERCENT_REVERSION,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Percentage reversion', [], 'work'),
                ),
                callback: fn (WorkReversion $workReversion) => $workReversion->getPercentageReversion(),
            ),
        ];
    }

    private function getRepository(): WorkReversionRepository
    {
        return $this->entityManager->getRepository(WorkReversion::class);
    }
}
