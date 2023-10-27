<?php

declare(strict_types=1);

namespace App\Pager\Contract;

use App\Entity\Contract\DistributionContractWorkRevenue;
use App\Enum\Pager\ColumnEnum;
use App\Form\Type\Pager\Contract\DistributionContractWorkRevenuePagerFormType;
use App\Model\Pager\Column;
use App\Model\Pager\ColumnHeader;
use App\Pager\Shared\AbstractPager;
use App\Repository\Shared\PagerRepositoryInterface;
use App\Tools\Parser\DateParser;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Translation\TranslatableMessage;

class DistributionContractRevenuePager extends AbstractPager
{
    protected static ?string $pagerId = 'app-pager-work';
    protected static array $defaultSort = [ColumnEnum::STARTS_AT->value => 'ASC'];

    protected static string $formType = DistributionContractWorkRevenuePagerFormType::class;

    protected static array $columns = [
        ColumnEnum::WORK,
        ColumnEnum::CHANNEL,
        ColumnEnum::STARTS_AT,
        ColumnEnum::ENDS_AT,
        ColumnEnum::AMOUNT,
    ];

    public function buildQuery(array $criteria = [], array $orderBy = [], int $limit = self::DEFAULT_LIMIT, int $offset = self::DEFAULT_OFFSET): void
    {
        $this->query = $this->getRepository()->getPagerQueryBuilder($criteria, $orderBy, $limit, $offset);
    }

    protected function configureColumnSchema(): void
    {
        static::$columnSchema = [
            new Column(
                id: ColumnEnum::WORK,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Work', [], 'work'),
                ),
                callback: fn (DistributionContractWorkRevenue $revenue) => $revenue->getContractWork()->getWork()->getName(),
            ),
            new Column(
                id: ColumnEnum::CHANNEL,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Channel', [], 'setting'),
                ),
                callback: fn (DistributionContractWorkRevenue $revenue) => $revenue->getBroadcastChannel()->getName(),
            ),
            new Column(
                id: ColumnEnum::STARTS_AT,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Starts at', [], 'misc'),
                ),
                callback: fn (DistributionContractWorkRevenue $revenue) => $revenue->getStartsAt()->format(DateParser::FR_DATE_FORMAT),
            ),
            new Column(
                id: ColumnEnum::ENDS_AT,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Ends at', [], 'misc'),
                ),
                callback: fn (DistributionContractWorkRevenue $revenue) => $revenue->getEndsAt()->format(DateParser::FR_DATE_FORMAT),
            ),
            new Column(
                id: ColumnEnum::AMOUNT,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Amount', [], 'misc'),
                ),
                callback: fn (DistributionContractWorkRevenue $revenue) => $revenue->getAmount(),
            ),
        ];
    }

    private function getRepository(): PagerRepositoryInterface|EntityRepository
    {
        return $this->entityManager->getRepository(DistributionContractWorkRevenue::class);
    }
}
