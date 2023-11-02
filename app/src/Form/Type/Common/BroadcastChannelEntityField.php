<?php

declare(strict_types=1);

namespace App\Form\Type\Common;

use App\Entity\Contract\DistributionContract;
use App\Entity\Setting\BroadcastChannel;
use App\Enum\Pager\ColumnEnum;
use App\Repository\Broadcast\BroadcastChannelRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class BroadcastChannelEntityField extends AbstractType
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
                'class' => BroadcastChannel::class,
                'query_builder' => function (Options $options) {
                    $criteria = [];
                    if (isset($options[ColumnEnum::DISTRIBUTION_CONTRACT])) {
                        $criteria[ColumnEnum::DISTRIBUTION_CONTRACT] = $options[ColumnEnum::DISTRIBUTION_CONTRACT];
                    }

                    return static fn (BroadcastChannelRepository $repository) => $repository->getPagerQueryBuilder(
                        criteria: $criteria,
                        orderBy: [ColumnEnum::NAME => 'ASC'],
                        limit: null
                    );
                },
                'choice_label' => 'name',
                'choice_attr' => fn (BroadcastChannel $channel) => [
                    'class' => $channel->isArchived() ? 'text-danger' : null,
                ],
                'choice_filter' => function (Options $options) {
                    if (!isset($options[ColumnEnum::INCLUDE])) {
                        return static fn (BroadcastChannel $channel) => !$channel->isArchived();
                    }

                    /** @var Collection $include */
                    $include = $options[ColumnEnum::INCLUDE];

                    return static fn (BroadcastChannel $channel) => !$channel->isArchived() || $include->contains($channel);
                },
                'group_by' => fn (BroadcastChannel $channel) => $this->translator->trans($channel->isArchived() ? 'Archived' : 'Active', [], 'misc'),
                'autocomplete' => true,
                'attr' => [
                    'data-controller' => 'form--autocomplete',
                ],
                'placeholder' => 'Select a channel',
                'translation_domain' => 'setting',
            ])
        ;
    }

    public function getParent(): string
    {
        return EntityType::class;
    }
}
