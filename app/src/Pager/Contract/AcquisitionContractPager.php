<?php

declare(strict_types=1);

namespace App\Pager\Contract;

use App\Entity\Contract\AcquisitionContract;
use App\Enum\Pager\ColumnEnum;
use App\Form\Type\Pager\Contract\AcquisitionContractPagerFormType;
use App\Model\Pager\Column;
use App\Model\Pager\ColumnHeader;
use App\Model\Pager\Field\LinkField;
use App\Model\Pager\Field\LinksField;
use App\Pager\Shared\AbstractPager;
use App\Repository\Shared\PagerRepositoryInterface;
use App\Tools\Parser\DateParser;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Translation\TranslatableMessage;

class AcquisitionContractPager extends AbstractPager
{
    protected static ?string $pagerId = 'app-pager-work';
    protected static array $defaultSort = [ColumnEnum::NAME->value => 'ASC'];

    protected static string $formType = AcquisitionContractPagerFormType::class;

    protected static array $columns = [
        ColumnEnum::NAME,
        ColumnEnum::BENEFICIARY,
        ColumnEnum::SIGNED_AT,
        ColumnEnum::STARTS_AT,
        ColumnEnum::ENDS_AT,
        ColumnEnum::WORKS_COUNT,
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
                callback: fn (AcquisitionContract $contract) => new LinkField(
                    value: $contract->getName(),
                    attributes: [
                        'href' => $this->router->generate('app_acquisition_contract_show', ['id' => $contract->getId()]),
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
                id: ColumnEnum::STARTS_AT,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Rights starts at', [], 'contract'),
                ),
                callback: fn (AcquisitionContract $contract) => $contract->getStartsAt()?->format(DateParser::FR_DATE_FORMAT),
            ),
            new Column(
                id: ColumnEnum::ENDS_AT,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Rights ends at', [], 'contract'),
                ),
                callback: fn (AcquisitionContract $contract) => $contract->getEndsAt()?->format(DateParser::FR_DATE_FORMAT),
            ),
            new Column(
                id: ColumnEnum::WORKS_COUNT,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Works', [], 'work'),
                    sortable: false,
                ),
                callback: fn (AcquisitionContract $contract) => $contract->getWorks()->count(),
            ),
            new Column(
                id: ColumnEnum::ACTIONS,
                header: new ColumnHeader(
                    callback: fn () => new TranslatableMessage('Actions', [], 'misc'),
                    sortable: false,
                ),
                callback: fn (AcquisitionContract $contract) => new LinksField([
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
