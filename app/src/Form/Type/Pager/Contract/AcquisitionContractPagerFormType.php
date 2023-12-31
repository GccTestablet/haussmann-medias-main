<?php

declare(strict_types=1);

namespace App\Form\Type\Pager\Contract;

use App\Enum\Pager\ColumnEnum;
use App\Form\Type\Common\CompanyEntityField;
use App\Form\Type\Common\TerritoryEntityField;
use App\Form\Type\Common\WorkEntityField;
use App\Form\Type\Pager\Shared\BasePagerFormType;
use App\Form\Type\Shared\DateRangeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AcquisitionContractPagerFormType extends BasePagerFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add(ColumnEnum::NAME, TextType::class, [
                'label' => 'Name',
            ])
            ->add(ColumnEnum::BENEFICIARIES, CompanyEntityField::class, [
                'label' => 'Beneficiaries',
                'multiple' => true,
            ])
            ->add(ColumnEnum::WORKS, WorkEntityField::class, [
                'label' => 'Works',
                'translation_domain' => 'work',
                'multiple' => true,
            ])
            ->add(ColumnEnum::TERRITORIES, TerritoryEntityField::class, [
                'label' => 'Territories',
                'translation_domain' => 'work',
                'multiple' => true,
            ])
            ->add(ColumnEnum::SIGNED_AT, DateRangeType::class, [
                'label' => 'Signed at',
            ])
            ->add(ColumnEnum::STARTS_AT, DateRangeType::class, [
                'label' => 'Rights starts at',
            ])
            ->add(ColumnEnum::ENDS_AT, DateRangeType::class, [
                'label' => 'Rights ends at',
            ])
            ->add(ColumnEnum::ARCHIVED, CheckboxType::class, [
                'label' => 'Show archive?',
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
