<?php

declare(strict_types=1);

namespace App\Pager\Work;

use App\Entity\Work\Work;
use App\Enum\Pager\ColumnEnum;
use App\Form\Type\Pager\Work\WorkPagerFormType;
use App\Model\Pager\Column;
use App\Model\Pager\ColumnHeader;
use App\Model\Pager\Field\CollectionField;
use App\Model\Pager\Field\IconField;
use App\Model\Pager\Field\LinkField;
use App\Model\Pager\Field\PopoverButtonField;
use App\Model\Pager\Field\SimpleArrayField;
use App\Pager\Shared\AbstractPager;
use App\Repository\Shared\PagerRepositoryInterface;
use App\Tools\Parser\StringParser;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Translation\TranslatableMessage;
use Twig\Environment;

class WorkPager extends AbstractPager
{
    protected static ?string $pagerId = 'app-pager-work';
    protected static array $defaultSort = [ColumnEnum::NAME => 'ASC'];

    protected static string $formType = WorkPagerFormType::class;

    protected static array $columns = [
        ColumnEnum::EXTRA,
        ColumnEnum::INTERNAL_ID,
        ColumnEnum::NAME,
        ColumnEnum::COUNTRIES,
        ColumnEnum::ACQUISITION_CONTRACT,
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
                callback: fn (Work $work) => new CollectionField([
                    $work->isArchived() ? new IconField(
                        icon: 'fas fa-archive',
                        attributes: [
                            'title' => new TranslatableMessage('Archived', [], 'misc'),
                            'class' => 'text-warning',
                        ]
                    ) : null,
                    new PopoverButtonField(
                        value: new IconField(
                            icon: 'circle-info',
                            attributes: [
                                'class' => 'text-primary',
                            ]
                        ),
                        popoverTitle: new TranslatableMessage('Territories', [], 'work'),
                        popoverContent: $this->twig->render('work/territory/_embedded/_list.html.twig', [
                            'title' => new TranslatableMessage('Territories and channels acquired', [], 'work'),
                            'workTerritories' => $work->getWorkTerritories(),
                        ]),
                        attributes: [
                            'title' => new TranslatableMessage('Territories', [], 'work'),
                            'class' => 'btn btn-sm',
                            'data-work-id' => $work->getId(),
                        ],
                    ),
                ])
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
                id: ColumnEnum::COUNTRIES,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Country', [], 'work'),
                    sortable: false,
                ),
                callback: fn (Work $work) => new SimpleArrayField(\array_map(
                    static fn (string $code) => Countries::getName($code),
                    $work->getCountries()
                )),
            ),
            new Column(
                id: ColumnEnum::ACQUISITION_CONTRACT,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Acquisition contract', [], 'contract')
                ),
                callback: fn (Work $work) => new LinkField(
                    value: StringParser::truncate($work->getAcquisitionContract()->getName(), 20, true),
                    attributes: [
                        'href' => $this->router->generate('app_acquisition_contract_show', [
                            'id' => $work->getAcquisitionContract()->getId(),
                        ]),
                        'title' => $work->getAcquisitionContract()->getName(),
                    ]
                ),
            ),
            new Column(
                id: ColumnEnum::ACTIONS,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Actions', [], 'misc'),
                    sortable: false,
                ),
                callback: fn (Work $work) => new CollectionField([
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
     * @return PagerRepositoryInterface|EntityRepository<Work>
     */
    private function getRepository(): PagerRepositoryInterface|EntityRepository
    {
        return $this->entityManager->getRepository(Work::class);
    }
}
