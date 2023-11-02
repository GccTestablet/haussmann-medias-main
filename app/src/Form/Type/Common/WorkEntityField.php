<?php

declare(strict_types=1);

namespace App\Form\Type\Common;

use App\Entity\Contract\DistributionContract;
use App\Entity\Work\Work;
use App\Enum\Pager\ColumnEnum;
use App\Repository\WorkRepository;
use App\Service\Security\SecurityManager;
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
            ])
            ->setAllowedTypes(ColumnEnum::DISTRIBUTION_CONTRACT, DistributionContract::class)
            ->setAllowedTypes(ColumnEnum::INCLUDE, Collection::class)
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
                'choice_filter' => function (Options $options) {
                    if (!isset($options[ColumnEnum::INCLUDE])) {
                        return static fn (?Work $work) => !$work?->isArchived();
                    }

                    /** @var Collection $include */
                    $include = $options[ColumnEnum::INCLUDE];

                    return static fn (Work $work) => !$work->isArchived() || $include->contains($work);
                },
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
}
