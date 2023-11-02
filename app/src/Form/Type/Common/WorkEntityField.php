<?php

declare(strict_types=1);

namespace App\Form\Type\Common;

use App\Entity\Contract\DistributionContract;
use App\Entity\Work\Work;
use App\Enum\Pager\ColumnEnum;
use App\Repository\WorkRepository;
use App\Service\Security\SecurityManager;
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
                ColumnEnum::DISTRIBUTION_CONTRACT->value,
            ])
            ->setAllowedTypes(ColumnEnum::DISTRIBUTION_CONTRACT->value, DistributionContract::class)
            ->setDefaults([
                'class' => Work::class,
                'query_builder' => function (Options $options) {
                    $criteria = [
                        ColumnEnum::COMPANY->value => $this->securityManager->getConnectedUser()->getConnectedOn(),
                    ];

                    if (isset($options[ColumnEnum::DISTRIBUTION_CONTRACT->value])) {
                        $criteria[ColumnEnum::DISTRIBUTION_CONTRACT->value] = $options[ColumnEnum::DISTRIBUTION_CONTRACT->value];
                    }

                    return static fn (WorkRepository $repository) => $repository->getPagerQueryBuilder(
                        criteria: $criteria,
                        orderBy: [ColumnEnum::NAME->value => 'ASC'],
                        limit: null
                    );
                },
                'choice_label' => fn (Work $work) => \sprintf('%s (%s)', $work->getName(), $work->getInternalId()),
                'choice_attr' => fn (Work $work) => [
                    'class' => $work->isArchived() ? 'text-danger' : null,
                ],
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
