<?php

declare(strict_types=1);

namespace App\Pager\Contract;

use App\Entity\Contract\DistributionContractWork;
use App\Enum\Pager\ColumnEnum;
use App\Form\Type\Pager\Contract\DistributionContractWorkPagerFormType;
use App\Model\Pager\Column;
use App\Model\Pager\ColumnHeader;
use App\Model\Pager\Field\AmountField;
use App\Model\Pager\Field\CollectionField;
use App\Model\Pager\Field\IconField;
use App\Model\Pager\Field\LinkField;
use App\Model\Pager\Field\PeriodField;
use App\Model\Pager\Field\PopoverButtonField;
use App\Pager\Shared\AbstractPager;
use App\Repository\Contract\DistributionContractWorkRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Translation\TranslatableMessage;
use Twig\Environment;

class DistributionContractWorkPager extends AbstractPager
{
    protected static ?string $pagerId = 'app-distribution-contract-work-pager';
    protected static array $defaultSort = [ColumnEnum::WORK->value => 'ASC'];

    protected static string $formType = DistributionContractWorkPagerFormType::class;

    protected static array $columns = [
        ColumnEnum::EXTRA,
        ColumnEnum::WORK,
        ColumnEnum::PERIOD,
        ColumnEnum::AMOUNT,
        ColumnEnum::ACTIONS,
    ];

    public function __construct(
        private readonly Environment $twig
    ) {}

    public function buildQuery(array $criteria = [], array $orderBy = [], int $limit = self::DEFAULT_LIMIT, int $offset = self::DEFAULT_OFFSET): void
    {
        $this->query = $this->getRepository()->getPagerQueryBuilder($criteria, $orderBy, $limit, $offset);
    }

    protected function configureColumnSchema(): void
    {
        static::$columnSchema = [
            new Column(
                id: ColumnEnum::EXTRA,
                header: new ColumnHeader(
                    callback: fn () => '',
                    sortable: false,
                ),
                callback: fn (DistributionContractWork $contractWork) => new PopoverButtonField(
                    value: new IconField(
                        icon: 'circle-info',
                        attributes: [
                            'class' => 'text-primary',
                        ]
                    ),
                    popoverTitle: new TranslatableMessage('Territories', [], 'work'),
                    popoverContent: $this->twig->render('work/territory/_embedded/_list.html.twig', [
                        'title' => new TranslatableMessage('Territories and channels distributed', [], 'contract'),
                        'workTerritories' => $contractWork->getWorkTerritories(),
                    ]),
                    attributes: [
                        'title' => new TranslatableMessage('Territories', [], 'work'),
                        'class' => 'btn btn-sm',
                        'data-work-id' => $contractWork->getId(),
                    ],
                )
            ),
            new Column(
                id: ColumnEnum::WORK,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Work', [], 'work'),
                ),
                callback: fn (DistributionContractWork $contractWork) => $contractWork->getWork()->getName(),
            ),
            new Column(
                id: ColumnEnum::PERIOD,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Period', [], 'misc'),
                ),
                callback: fn (DistributionContractWork $contractWork) => new PeriodField(
                    $contractWork->getStartsAt(),
                    $contractWork->getEndsAt(),
                ),
            ),
            new Column(
                id: ColumnEnum::AMOUNT,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Amount', [], 'misc'),
                ),
                callback: fn (DistributionContractWork $contractWork) => new AmountField(
                    $contractWork->getAmount(),
                    $contractWork->getCurrency()
                ),
            ),
            new Column(
                id: ColumnEnum::ACTIONS,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Actions', [], 'misc'),
                    sortable: false,
                ),
                callback: fn (DistributionContractWork $contractWork) => new CollectionField([
                    new LinkField(
                        value: new TranslatableMessage('Update', [], 'misc'),
                        attributes: [
                            'href' => $this->router->generate('app_distribution_contract_work_update', [
                                'contract' => $contractWork->getDistributionContract()->getId(),
                                'work' => $contractWork->getWork()->getId(),
                            ]),
                            'class' => 'btn btn-sm btn-warning',
                        ]
                    ),
                    new LinkField(
                        value: new TranslatableMessage('Manage territories', [], 'work'),
                        attributes: [
                            'href' => $this->router->generate('app_distribution_contract_work_territory_manage', [
                                'contract' => $contractWork->getDistributionContract()->getId(),
                                'work' => $contractWork->getWork()->getId(),
                            ]),
                            'class' => 'btn btn-sm btn-warning',
                        ]
                    ),
                ]),
            ),
        ];
    }

    /**
     * @return DistributionContractWorkRepository|EntityRepository<DistributionContractWork>
     */
    private function getRepository(): DistributionContractWorkRepository|EntityRepository
    {
        return $this->entityManager->getRepository(DistributionContractWork::class);
    }
}
