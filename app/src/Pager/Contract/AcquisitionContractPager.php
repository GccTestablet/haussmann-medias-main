<?php

declare(strict_types=1);

namespace App\Pager\Contract;

use App\Entity\Contract\AcquisitionContract;
use App\Enum\Pager\ColumnEnum;
use App\Form\Type\Pager\Contract\AcquisitionContractPagerFormType;
use App\Model\Pager\Column;
use App\Model\Pager\ColumnHeader;
use App\Model\Pager\Field\CollectionField;
use App\Model\Pager\Field\IconField;
use App\Model\Pager\Field\LinkField;
use App\Model\Pager\Field\PeriodField;
use App\Pager\Shared\AbstractPager;
use App\Repository\Shared\PagerRepositoryInterface;
use App\Tools\Parser\DateParser;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Translation\TranslatableMessage;

class AcquisitionContractPager extends AbstractPager
{
    protected static ?string $pagerId = 'app-acquisition-contract-pager';
    protected static array $defaultSort = [ColumnEnum::NAME->value => 'ASC'];

    protected static string $formType = AcquisitionContractPagerFormType::class;

    protected static array $columns = [
        ColumnEnum::ARCHIVED,
        ColumnEnum::NAME,
        ColumnEnum::ACQUIRER,
        ColumnEnum::BENEFICIARY,
        ColumnEnum::SIGNED_AT,
        ColumnEnum::PERIOD,
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
                id: ColumnEnum::ARCHIVED,
                header: new ColumnHeader(
                    callback: fn () => null,
                    sortable: false,
                ),
                callback: fn (AcquisitionContract $contract) => $contract->isArchived() ? new IconField(
                    icon: 'fas fa-archive',
                    attributes: [
                        'title' => new TranslatableMessage('Archived', [], 'misc'),
                        'class' => 'text-warning',
                    ]
                ) : null,
            ),
            new Column(
                id: ColumnEnum::NAME,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Name', [], 'misc'),
                ),
                callback: fn (AcquisitionContract $contract) => new LinkField(
                    value: $contract->getName(),
                    attributes: [
                        'href' => $this->router->generate('app_acquisition_contract_show', ['id' => $contract->getId()]),
                    ]
                ),
            ),
            new Column(
                id: ColumnEnum::ACQUIRER,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Acquirer', [], 'contract'),
                ),
                callback: fn (AcquisitionContract $contract) => new LinkField(
                    value: $contract->getCompany()->getName(),
                    attributes: [
                        'href' => $this->router->generate('app_company_show', ['id' => $contract->getCompany()->getId()]),
                    ]
                ),
            ),
            new Column(
                id: ColumnEnum::BENEFICIARY,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Beneficiary', [], 'contract'),
                ),
                callback: fn (AcquisitionContract $contract) => new LinkField(
                    value: $contract->getBeneficiary()->getName(),
                    attributes: [
                        'href' => $this->router->generate('app_company_show', ['id' => $contract->getBeneficiary()->getId()]),
                    ]
                ),
            ),
            new Column(
                id: ColumnEnum::SIGNED_AT,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Signed at', [], 'contract'),
                ),
                callback: fn (AcquisitionContract $contract) => $contract->getSignedAt()->format(DateParser::FR_DATE_FORMAT),
            ),
            new Column(
                id: ColumnEnum::PERIOD,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Rights period', [], 'contract'),
                ),
                callback: fn (AcquisitionContract $contract) => new PeriodField(
                    $contract->getStartsAt(),
                    $contract->getEndsAt(),
                )
            ),
            new Column(
                id: ColumnEnum::WORKS,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Works', [], 'work'),
                    sortable: false,
                ),
                callback: fn (AcquisitionContract $contract) => \implode(', ', $contract->getWorks()->map(fn ($work) => $work->getName())->toArray()),
            ),
            new Column(
                id: ColumnEnum::ACTIONS,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Actions', [], 'misc'),
                    sortable: false,
                ),
                callback: fn (AcquisitionContract $contract) => new CollectionField([
                    new LinkField(
                        value: new TranslatableMessage('Update', [], 'misc'),
                        attributes: [
                            'href' => $this->router->generate('app_acquisition_contract_update', [
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
        return $this->entityManager->getRepository(AcquisitionContract::class);
    }
}
