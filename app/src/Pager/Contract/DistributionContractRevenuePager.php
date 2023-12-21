<?php

declare(strict_types=1);

namespace App\Pager\Contract;

use App\Entity\Contract\DistributionContractWorkRevenue;
use App\Enum\Pager\ColumnEnum;
use App\Form\Type\Pager\Contract\DistributionContractWorkRevenuePagerFormType;
use App\Model\Pager\Column;
use App\Model\Pager\ColumnHeader;
use App\Model\Pager\Field\AmountField;
use App\Model\Pager\Field\CollectionField;
use App\Model\Pager\Field\FieldInterface;
use App\Model\Pager\Field\IconField;
use App\Model\Pager\Field\LinkField;
use App\Model\Pager\Field\PeriodField;
use App\Pager\Shared\AbstractPager;
use App\Repository\Contract\DistributionContractWorkRevenueRepository;
use Egulias\EmailValidator\Parser\IDLeftPart;
use Symfony\Component\Translation\TranslatableMessage;
use function PHPUnit\Framework\callback;

class DistributionContractRevenuePager extends AbstractPager
{
    protected static ?string $pagerId = 'app-distribution-contract-pager';
    protected static array $defaultSort = [ColumnEnum::STARTS_AT => 'DESC'];

    protected static string $formType = DistributionContractWorkRevenuePagerFormType::class;

    protected static array $columns = [
        ColumnEnum::WORK,
        ColumnEnum::CHANNEL,
        ColumnEnum::PERIOD,
        ColumnEnum::AMOUNT,
        ColumnEnum::ACTIONS,
    ];

    /**
     * @var array <string, string>
     */
    private array $filteredSumByCurrency = [];

    public function buildQuery(array $criteria = [], array $orderBy = [], int $limit = self::DEFAULT_LIMIT, int $offset = self::DEFAULT_OFFSET): void
    {
        $this->query = $this->getRepository()->getPagerQueryBuilder($criteria, $orderBy, $limit, $offset);
        $this->filteredSumByCurrency = $this->getRepository()->getFilteredSumByCurrency($criteria, $orderBy);
    }

    /**
     * @return array<string, FieldInterface|string>
     */
    public function getFooter(): array
    {
        $collection = new CollectionField(\array_map(
            static fn (string $sum, string $currency) => new AmountField($sum, $currency),
            $this->filteredSumByCurrency,
            \array_keys($this->filteredSumByCurrency),
        ), '<br />');

        return [
            ColumnEnum::AMOUNT => $collection,
        ];
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
                id: ColumnEnum::PERIOD,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Period', [], 'misc'),
                ),
                callback: fn (DistributionContractWorkRevenue $revenue) => new PeriodField(
                    $revenue->getStartsAt(),
                    $revenue->getEndsAt(),
                ),
            ),
            new Column(
                id: ColumnEnum::AMOUNT,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Amount', [], 'misc'),
                ),
                callback: fn (DistributionContractWorkRevenue $revenue) => new AmountField($revenue->getAmount(), $revenue->getCurrency()),
            ),
            new Column(
                id: ColumnEnum::ACTIONS,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Actions', [], 'misc'),
                    sortable: false,
                ),
                callback: fn (DistributionContractWorkRevenue $contractWork) => new CollectionField([
                    new LinkField(
                        value: new TranslatableMessage('Update', [], 'misc'),
                        attributes: [
                            'href' => $this->router->generate('app_distribution_contract_work_revenue_update', [
                                'contract' => $contractWork->getContractWork()->getId(),
                            ]),
                            'class' => 'btn btn-sm btn-warning',
                        ]
                    ),
                ]),
            ),
        ];
    }

    private function getRepository(): DistributionContractWorkRevenueRepository
    {
        return $this->entityManager->getRepository(DistributionContractWorkRevenue::class);
    }
}
