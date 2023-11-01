<?php

declare(strict_types=1);

namespace App\Form\Type\Common;

use App\Entity\Contract\DistributionContract;
use App\Entity\Setting\BroadcastChannel;
use App\Enum\Pager\ColumnEnum;
use App\Repository\Broadcast\BroadcastChannelRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BroadcastChannelEntityField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefined([
                ColumnEnum::DISTRIBUTION_CONTRACT->value,
            ])
            ->setAllowedTypes(ColumnEnum::DISTRIBUTION_CONTRACT->value, DistributionContract::class)
            ->setDefaults([
                'class' => BroadcastChannel::class,
                'query_builder' => function (Options $options) {
                    $criteria = [];
                    if (isset($options[ColumnEnum::DISTRIBUTION_CONTRACT->value])) {
                        $criteria[ColumnEnum::DISTRIBUTION_CONTRACT->value] = $options[ColumnEnum::DISTRIBUTION_CONTRACT->value];
                    }

                    return static fn (BroadcastChannelRepository $repository) => $repository->getPagerQueryBuilder(
                        criteria: $criteria,
                        orderBy: [ColumnEnum::NAME->value => 'ASC'],
                        limit: null
                    );
                },
                'choice_label' => 'name',
                'choice_attr' => fn (BroadcastChannel $channel) => [
                    'class' => $channel->isArchived() ? 'text-decoration-line-through' : null,
                ],
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
