<?php

declare(strict_types=1);

namespace App\Pager\Contract;

use App\Entity\Contract\DistributionContract;
use App\Entity\Setting\BroadcastChannel;
use App\Entity\Work\Work;
use App\Enum\Pager\ColumnEnum;
use App\Form\Type\Pager\Contract\DistributionContractPagerFormType;
use App\Model\Pager\Column;
use App\Model\Pager\ColumnHeader;
use App\Model\Pager\Field\CollectionField;
use App\Model\Pager\Field\LinkField;
use App\Pager\Shared\AbstractPager;
use App\Repository\Shared\PagerRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Translation\TranslatableMessage;

class DistributionContractPager extends AbstractPager
{
    protected static ?string $pagerId = 'app-distribution-contract-pager';
    protected static array $defaultSort = [ColumnEnum::NAME->value => 'ASC'];

    protected static string $formType = DistributionContractPagerFormType::class;

    protected static array $columns = [
        ColumnEnum::NAME,
        ColumnEnum::DISTRIBUTOR,
        ColumnEnum::CHANNELS,
        ColumnEnum::WORKS,
        ColumnEnum::ACTIONS,
    ];

    public function buildQuery(array $criteria = [], array $orderBy = [], int $limit = self::DEFAULT_LIMIT, int $offset = self::DEFAULT_OFFSET): void
    {
        $this->query = $this->getRepository()->getPagerQueryBuilder($criteria, $orderBy, $limit, $offset);
    }

    protected function configureColumnSchema(): void
    {
        static::$columnSchema = [
            new Column(
                id: ColumnEnum::NAME,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Name', [], 'misc'),
                ),
                callback: fn (DistributionContract $contract) => new LinkField(
                    value: $contract->getName(),
                    attributes: [
                        'href' => $this->router->generate('app_distribution_contract_show', ['id' => $contract->getId()]),
                    ]
                ),
            ),
            new Column(
                id: ColumnEnum::DISTRIBUTOR,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Distributor', [], 'contract'),
                ),
                callback: fn (DistributionContract $contract) => new LinkField(
                    value: $contract->getDistributor()->getName(),
                    attributes: [
                        'href' => $this->router->generate('app_company_show', ['id' => $contract->getDistributor()->getId()]),
                    ]
                ),
            ),
            new Column(
                id: ColumnEnum::CHANNELS,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Broadcast channels', [], 'work'),
                    sortable: false,
                ),
                callback: fn (DistributionContract $contract) => \implode(', ', $contract->getBroadcastChannels()->map(fn (BroadcastChannel $channel) => $channel->getName())->toArray()),
            ),
            new Column(
                id: ColumnEnum::WORKS,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Works', [], 'work'),
                    sortable: false,
                ),
                callback: fn (DistributionContract $contract) => \implode(', ', $contract->getWorks()->map(fn (Work $work) => $work->getName())->toArray()),
            ),
            new Column(
                id: ColumnEnum::ACTIONS,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Actions', [], 'misc'),
                    sortable: false,
                ),
                callback: fn (DistributionContract $contract) => new CollectionField([
                    new LinkField(
                        value: new TranslatableMessage('Update', [], 'misc'),
                        attributes: [
                            'href' => $this->router->generate('app_distribution_contract_update', [
                                'id' => $contract->getId(),
                            ]),
                            'class' => 'btn btn-sm btn-warning',
                        ]
                    ),
                ]),
            ),
        ];
    }

    private function getRepository(): PagerRepositoryInterface|EntityRepository
    {
        return $this->entityManager->getRepository(DistributionContract::class);
    }
}
