<?php

declare(strict_types=1);

namespace App\Form\Type\Pager\Contract;

use App\Enum\Pager\ColumnEnum;
use App\Form\Type\Common\BroadcastChannelEntityField;
use App\Form\Type\Common\WorkEntityField;
use App\Form\Type\Pager\Shared\BasePagerFormType;
use App\Form\Type\Shared\DateRangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DistributionContractWorkRevenuePagerFormType extends BasePagerFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add(ColumnEnum::WORKS->value, WorkEntityField::class, [
                'label' => 'Works',
                'translation_domain' => 'work',
                'multiple' => true,
            ])
            ->add(ColumnEnum::CHANNELS->value, BroadcastChannelEntityField::class, [
                'label' => 'Broadcast channels',
                'multiple' => true,
            ])
            ->add(ColumnEnum::STARTS_AT->value, DateRangeType::class, [
                'label' => 'Rights starts at',
            ])
            ->add(ColumnEnum::ENDS_AT->value, DateRangeType::class, [
                'label' => 'Rights ends at',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'translation_domain' => 'contract',
            ])
        ;
    }
}
