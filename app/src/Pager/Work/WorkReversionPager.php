<?php

declare(strict_types=1);

namespace App\Pager\Work;

use App\Entity\Work\WorkReversion;
use App\Enum\Pager\ColumnEnum;
use App\Model\Pager\Column;
use App\Model\Pager\ColumnHeader;
use App\Model\Pager\Field\CollectionField;
use App\Model\Pager\Field\LinkField;
use App\Pager\Shared\AbstractPager;
use App\Repository\Work\WorkReversionRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Translation\TranslatableMessage;

class WorkReversionPager extends AbstractPager
{
    protected static ?string $pagerId = 'app-pager-work-reversion';
    protected static array $defaultSort = [ColumnEnum::TYPE->value => 'ASC'];

    protected static array $columns = [
        ColumnEnum::CHANNEL,
        ColumnEnum::PERCENT_REVERSION,
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
            new Column(
                id: ColumnEnum::ACTIONS,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Actions', [], 'misc'),
                    sortable: false,
                ),
                callback: fn (WorkReversion $workReversion) => new CollectionField([
                        new LinkField(
                            value: new TranslatableMessage('Update', [], 'misc'),
                            attributes: [
                                'href' => $this->router->generate('app_work_reversion_update', [
                                    'work' => $workReversion->getWork()->getId(),
                                    'id' => $workReversion->getId()]
                                ),
                                'class' => 'btn btn-sm btn-warning',
                            ],
                        ),
                        new LinkField(
                            value: new TranslatableMessage('Remove', [], 'misc'),
                            attributes: [
                                'href' => $this->router->generate('app_work_reversion_remove', [
                                        'work' => $workReversion->getWork()->getId(),
                                        'id' => $workReversion->getId()]
                                ),
                                'class' => 'btn btn-sm btn-danger',
                            ],
                        ),
                    ]),
            ),
        ];
    }

    /**
     * @return WorkReversionRepository|EntityRepository<WorkReversion>
     */
    private function getRepository(): WorkReversionRepository|EntityRepository
    {
        return $this->entityManager->getRepository(WorkReversion::class);
    }
}
