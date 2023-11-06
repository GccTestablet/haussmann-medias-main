<?php

declare(strict_types=1);

namespace App\Form\Type\Common;

use App\Entity\Contract\DistributionContract;
use App\Entity\Work\Work;
use App\Enum\Pager\ColumnEnum;
use App\Repository\WorkRepository;
use App\Service\Security\SecurityManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class WorkEntityField extends AbstractType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly SecurityManager $securityManager,
    ) {}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined([
                ColumnEnum::DISTRIBUTION_CONTRACT,
                ColumnEnum::INCLUDE,
                ColumnEnum::EXCLUDE,
            ])
            ->setAllowedTypes(ColumnEnum::DISTRIBUTION_CONTRACT, DistributionContract::class)
            ->setAllowedTypes(ColumnEnum::INCLUDE, Collection::class)
            ->setAllowedTypes(ColumnEnum::EXCLUDE, Collection::class)
            ->setDefaults([
                'class' => Work::class,
                'query_builder' => function (Options $options) {
                    $criteria = [
                        ColumnEnum::COMPANY => $this->securityManager->getConnectedUser()->getConnectedOn(),
                    ];

                    if (isset($options[ColumnEnum::DISTRIBUTION_CONTRACT])) {
                        $criteria[ColumnEnum::DISTRIBUTION_CONTRACT] = $options[ColumnEnum::DISTRIBUTION_CONTRACT];
                    }

                    return static fn (WorkRepository $repository) => $repository->getPagerQueryBuilder(
                        criteria: $criteria,
                        orderBy: [ColumnEnum::NAME => 'ASC'],
                        limit: null
                    );
                },
                'choice_label' => fn (Work $work) => \sprintf('%s (%s)', $work->getName(), $work->getInternalId()),
                'choice_attr' => fn (Work $work) => [
                    'class' => $work->isArchived() ? 'text-danger' : null,
                ],
                'choice_filter' => fn (Options $options) => fn (?Work $work) => $this->isWorkShown($options, $work),
                'group_by' => fn (Work $work) => $this->translator->trans($work->isArchived() ? 'Archived' : 'Active', [], 'misc'),
                'autocomplete' => true,
                'placeholder' => 'Select a work',
                'translation_domain' => 'work',
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

    private function isWorkShown(Options $options, ?Work $work): bool
    {
        if (!$work) {
            return false;
        }

        /** @var ?Collection $include */
        $include = $options[ColumnEnum::INCLUDE] ?? new ArrayCollection();
        if ($include->contains($work)) {
            return true;
        }

        /** @var ?Collection $exclude */
        $exclude = $options[ColumnEnum::EXCLUDE] ?? new ArrayCollection();
        if ($exclude->contains($work)) {
            return false;
        }

        return !$work->isArchived();
    }
}
