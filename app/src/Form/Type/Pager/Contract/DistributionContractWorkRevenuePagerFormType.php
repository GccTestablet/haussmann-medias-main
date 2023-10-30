<?php

declare(strict_types=1);

namespace App\Form\Type\Pager\Contract;

use App\Entity\Contract\DistributionContract;
use App\Enum\Pager\ColumnEnum;
use App\Form\Type\Common\BroadcastChannelEntityField;
use App\Form\Type\Common\WorkEntityField;
use App\Form\Type\Pager\Shared\BasePagerFormType;
use App\Form\Type\Shared\DateRangeType;
use App\Model\Pager\FilterCollection;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DistributionContractWorkRevenuePagerFormType extends BasePagerFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        /** @var FilterCollection $filters */
        $filters = $options['filters'];

        /** @var DistributionContract $distributionContract */
        $distributionContract = $filters->getFilter(ColumnEnum::DISTRIBUTION_CONTRACT)?->getValue();

        $builder
            ->add(ColumnEnum::WORKS->value, WorkEntityField::class, [
                'label' => 'Works',
                'translation_domain' => 'work',
                'multiple' => true,
                ColumnEnum::DISTRIBUTION_CONTRACT->value => $distributionContract,
            ])
            ->add(ColumnEnum::CHANNELS->value, BroadcastChannelEntityField::class, [
                'label' => 'Broadcast channels',
                'multiple' => true,
                ColumnEnum::DISTRIBUTION_CONTRACT->value => $distributionContract,
            ])
            ->add(ColumnEnum::ENDS_AT->value, DateRangeType::class, [
                'label' => 'Revenues to date',
                'translation_domain' => 'misc',
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
