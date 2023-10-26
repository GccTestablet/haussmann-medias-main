<?php

declare(strict_types=1);

namespace App\Pager\Work;

use App\Entity\Work\Work;
use App\Enum\Pager\ColumnEnum;
use App\Model\Pager\Column;
use App\Model\Pager\ColumnHeader;
use App\Model\Pager\Field\IconField;
use App\Model\Pager\Field\LinkField;
use App\Model\Pager\Field\LinksField;
use App\Model\Pager\Field\PopoverButtonField;
use App\Pager\Shared\AbstractPager;
use App\Repository\WorkRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Translation\TranslatableMessage;
use Twig\Environment;

class WorkPager extends AbstractPager
{
    protected static ?string $pagerId = 'app-pager-work';
    protected static array $defaultSort = [ColumnEnum::INTERNAL_ID->value => 'ASC'];

    protected static array $columns = [
        ColumnEnum::ID,
        ColumnEnum::INTERNAL_ID,
        ColumnEnum::NAME,
        ColumnEnum::CONTRACT,
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
                id: ColumnEnum::ID,
                header: new ColumnHeader(
                    callback: fn () => '',
                    sortable: false,
                ),
                callback: fn (Work $work) => new PopoverButtonField(
                    value: new IconField(
                        icon: 'circle-exclamation',
                        attributes: [
                            'class' => 'text-primary',
                        ]
                    ),
                    popoverTitle: new TranslatableMessage('Territories', [], 'work'),
                    popoverContent: $this->twig->render('work/territory/_embedded/_list.html.twig', [
                        'workTerritories' => $work->getWorkTerritories(),
                    ]),
                    attributes: [
                        'title' => new TranslatableMessage('Territories', [], 'work'),
                        'class' => 'btn btn-sm',
                    ],
                )
            ),
            new Column(
                id: ColumnEnum::INTERNAL_ID,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Internal Id', [], 'work')
                ),
                callback: fn (Work $work) => new LinkField(
                    value: $work->getInternalId(),
                    attributes: [
                        'href' => $this->router->generate('app_work_show', [
                            'id' => $work->getId(),
                        ]),
                    ]
                ),
            ),
            new Column(
                id: ColumnEnum::NAME,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Name', [], 'work')
                ),
                callback: fn (Work $work) => $work->getName(),
            ),
            new Column(
                id: ColumnEnum::CONTRACT,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Contract', [], 'work')
                ),
                callback: fn (Work $work) => new LinkField(
                    value: $work->getAcquisitionContract()->getName(),
                    attributes: [
                        'href' => $this->router->generate('app_acquisition_contract_show', [
                            'id' => $work->getAcquisitionContract()->getId(),
                        ]),
                    ]
                ),
            ),
            new Column(
                id: ColumnEnum::ACTIONS,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Actions', [], 'misc'),
                    sortable: false,
                ),
                callback: fn (Work $work) => new LinksField([
                    new LinkField(
                        value: new TranslatableMessage('Update', [], 'misc'),
                        attributes: [
                            'href' => $this->router->generate('app_work_update', [
                                'id' => $work->getId(),
                            ]),
                            'class' => 'btn btn-sm btn-warning',
                        ]
                    ),
                ]),
            ),
        ];
    }

    /**
     * @return WorkRepository|EntityRepository<Work>
     */
    private function getRepository(): WorkRepository|EntityRepository
    {
        return $this->entityManager->getRepository(Work::class);
    }
}
