<?php

declare(strict_types=1);

namespace App\Form\Type\Common;

use App\Entity\Setting\AdaptationCostType;
use App\Enum\Pager\ColumnEnum;
use App\Repository\Setting\AdaptationCostTypeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdaptationCostTypeEntityField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setRequired([
                ColumnEnum::TYPE->value,
            ])
            ->setAllowedTypes(ColumnEnum::TYPE->value, [AdaptationCostType::class, 'null'])
            ->setDefaults([
                'placeholder' => 'Select a cost type',
                'class' => AdaptationCostType::class,
                'query_builder' => fn (AdaptationCostTypeRepository $repository) => $repository
                    ->createQueryBuilder('act')
                    ->orderBy('act.name', 'ASC'),
                'choice_filter' => function (Options $options) {
                    $type = $options[ColumnEnum::TYPE->value];

                    return static fn (?AdaptationCostType $costType) => !$costType?->isArchived() || $costType === $type;
                },
                'choice_label' => 'name',
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
