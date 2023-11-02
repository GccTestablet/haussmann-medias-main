<?php

declare(strict_types=1);

namespace App\Form\Type\Pager\Contract;

use App\Entity\Contract\DistributionContract;
use App\Enum\Pager\ColumnEnum;
use App\Form\Type\Common\BroadcastChannelEntityField;
use App\Form\Type\Common\TerritoryEntityField;
use App\Form\Type\Common\WorkEntityField;
use App\Form\Type\Pager\Shared\BasePagerFormType;
use App\Model\Pager\FilterCollection;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DistributionContractWorkPagerFormType extends BasePagerFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        /** @var FilterCollection $filters */
        $filters = $options['filters'];

        /** @var DistributionContract $distributionContract */
        $distributionContract = $filters->getFilter(ColumnEnum::DISTRIBUTION_CONTRACT)?->getValue();

        $builder
            ->add(ColumnEnum::WORKS, WorkEntityField::class, [
                'label' => 'Works',
                'translation_domain' => 'work',
                'multiple' => true,
                ColumnEnum::DISTRIBUTION_CONTRACT => $distributionContract,
            ])
            ->add(ColumnEnum::TERRITORIES, TerritoryEntityField::class, [
                'label' => 'Territories',
                'multiple' => true,
            ])
            ->add(ColumnEnum::CHANNELS, BroadcastChannelEntityField::class, [
                'label' => 'Broadcast channels',
                'multiple' => true,
                ColumnEnum::DISTRIBUTION_CONTRACT => $distributionContract,
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
