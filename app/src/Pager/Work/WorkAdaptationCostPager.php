<?php

declare(strict_types=1);

namespace App\Pager\Work;

use App\Entity\Work\WorkAdaptation;
use App\Enum\Pager\ColumnEnum;
use App\Model\Pager\Column;
use App\Model\Pager\ColumnHeader;
use App\Model\Pager\Field\AmountField;
use App\Model\Pager\Field\LinkField;
use App\Pager\Shared\AbstractPager;
use App\Repository\Work\WorkAdaptationRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Translation\TranslatableMessage;

class WorkAdaptationCostPager extends AbstractPager
{
    protected static ?string $pagerId = 'app-pager-work-adaptation-cost';
    protected static array $defaultSort = [ColumnEnum::TYPE => 'ASC'];

    protected static array $columns = [
        ColumnEnum::TYPE,
        ColumnEnum::AMOUNT,
        ColumnEnum::COMMENT,
        ColumnEnum::ACTIONS,
    ];

    public function buildQuery(array $criteria = [], array $orderBy = [], int $limit = self::DEFAULT_LIMIT, int $offset = self::DEFAULT_OFFSET): void
    {
        $this->query = $this->getRepository()->getPagerQueryBuilder($criteria, $orderBy, $limit, $offset);
    }

    protected function configureColumnSchema(): void
    {
        self::$columnSchema = [
            new Column(
                id: ColumnEnum::TYPE,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Type', [], 'misc'),
                ),
                callback: fn (WorkAdaptation $workAdaptation) => $workAdaptation->getType()->getName(),
            ),
            new Column(
                id: ColumnEnum::AMOUNT,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Amount', [], 'misc'),
                ),
                callback: fn (WorkAdaptation $workAdaptation) => new AmountField($workAdaptation->getAmount(), $workAdaptation->getCurrency()),
            ),
            new Column(
                id: ColumnEnum::COMMENT,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Comment', [], 'misc'),
                    sortable: false,
                ),
                callback: fn (WorkAdaptation $workAdaptation) => $workAdaptation->getComment(),
            ),
            new Column(
                id: ColumnEnum::ACTIONS,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Actions', [], 'misc'),
                    sortable: false,
                ),
                callback: fn (WorkAdaptation $workAdaptation) => new LinkField(
                    value: new TranslatableMessage('Update', [], 'misc'),
                    attributes: [
                        'href' => $this->router->generate('app_work_adaptation_update', [
                            'work' => $workAdaptation->getWork()->getId(),
                            'id' => $workAdaptation->getId()]
                        ),
                        'class' => 'btn btn-sm btn-warning',
                    ],
                ),
            ),
        ];
    }

    /**
     * @return WorkAdaptationRepository|EntityRepository<WorkAdaptation>
     */
    private function getRepository(): WorkAdaptationRepository|EntityRepository
    {
        return $this->entityManager->getRepository(WorkAdaptation::class);
    }
}
