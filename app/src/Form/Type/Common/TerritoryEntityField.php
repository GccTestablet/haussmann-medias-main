<?php

declare(strict_types=1);

namespace App\Form\Type\Common;

use App\Entity\Contract\DistributionContract;
use App\Entity\Setting\Territory;
use App\Enum\Pager\ColumnEnum;
use App\Repository\Setting\TerritoryRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class TerritoryEntityField extends AbstractType
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined([
                ColumnEnum::DISTRIBUTION_CONTRACT,
                ColumnEnum::INCLUDE,
            ])
            ->setAllowedTypes(ColumnEnum::DISTRIBUTION_CONTRACT, DistributionContract::class)
            ->setAllowedTypes(ColumnEnum::INCLUDE, Collection::class)
            ->setDefaults([
                'class' => Territory::class,
                'query_builder' => function (Options $options) {
                    $criteria = [];
                    if (isset($options[ColumnEnum::DISTRIBUTION_CONTRACT])) {
                        $criteria[ColumnEnum::DISTRIBUTION_CONTRACT] = $options[ColumnEnum::DISTRIBUTION_CONTRACT];
                    }

                    return static fn (TerritoryRepository $repository) => $repository->getPagerQueryBuilder(
                        criteria: $criteria,
                        orderBy: [ColumnEnum::NAME => 'ASC'],
                        limit: null
                    );
                },
                'choice_label' => 'name',
                'choice_attr' => fn (Territory $territory) => [
                    'class' => $territory->isArchived() ? 'text-danger' : null,
                ],
                'choice_filter' => function (Options $options) {
                    if (!isset($options[ColumnEnum::INCLUDE])) {
                        return static fn (Territory $territory) => !$territory->isArchived();
                    }

                    /** @var Collection $include */
                    $include = $options[ColumnEnum::INCLUDE];

                    return static fn (Territory $territory) => !$territory->isArchived() || $include->contains($territory);
                },
                'group_by' => fn (Territory $territory) => $this->translator->trans($territory->isArchived() ? 'Archived' : 'Active', [], 'misc'),
                'autocomplete' => true,
                'attr' => [
                    'data-controller' => 'form--autocomplete',
                ],
            ])
        ;
    }

    public function getParent(): string
    {
        return EntityType::class;
    }
}
