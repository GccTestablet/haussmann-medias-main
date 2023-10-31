<?php

declare(strict_types=1);

namespace App\Form\Type\Pager\Contract;

use App\Enum\Pager\ColumnEnum;
use App\Form\Type\Common\BroadcastChannelEntityField;
use App\Form\Type\Common\CompanyEntityField;
use App\Form\Type\Common\WorkEntityField;
use App\Form\Type\Pager\Shared\BasePagerFormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DistributionContractPagerFormType extends BasePagerFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add(ColumnEnum::NAME->value, TextType::class, [
                'label' => 'Name',
            ])
            ->add(ColumnEnum::DISTRIBUTORS->value, CompanyEntityField::class, [
                'label' => 'Distributors',
                'multiple' => true,
            ])
            ->add(ColumnEnum::WORKS->value, WorkEntityField::class, [
                'label' => 'Works',
                'translation_domain' => 'work',
                'multiple' => true,
            ])
            ->add(ColumnEnum::CHANNELS->value, BroadcastChannelEntityField::class, [
                'label' => 'Broadcast channels',
                'translation_domain' => 'setting',
                'multiple' => true,
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