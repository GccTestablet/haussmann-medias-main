<?php

declare(strict_types=1);

namespace App\Form\Type\Pager\Work;

use App\Enum\Pager\ColumnEnum;
use App\Enum\Work\WorkQuotaEnum;
use App\Form\Type\Common\CompanyEntityField;
use App\Form\Type\Pager\Shared\BasePagerFormType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkPagerFormType extends BasePagerFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add(ColumnEnum::INTERNAL_ID->value, TextType::class, [
                'label' => 'Internal Id',
            ])
            ->add(ColumnEnum::IMDB_ID->value, TextType::class, [
                'label' => 'Imdb id',
            ])
            ->add(ColumnEnum::NAME->value, TextType::class, [
                'label' => 'Title (French or original)',
            ])
            ->add(ColumnEnum::ACQUISITION_CONTRACT_NAME->value, TextType::class, [
                'label' => 'Acquisition contract name',
            ])
            ->add(ColumnEnum::BENEFICIARIES->value, CompanyEntityField::class, [
                'label' => 'Acquisition contract beneficiary',
                'attr' => [
                    'placeholder' => 'Select one or more beneficiaries',
                ],
                'multiple' => true,
            ])
            ->add(ColumnEnum::COUNTRIES->value, CountryType::class, [
                'attr' => [
                    'placeholder' => 'Select one or more countries',
                ],
                'translation_domain' => 'misc',
                'multiple' => true,
                'autocomplete' => true,
            ])
            ->add(ColumnEnum::QUOTAS->value, EnumType::class, [
                'class' => WorkQuotaEnum::class,
                'choice_label' => fn (WorkQuotaEnum $originWorkEnum) => $originWorkEnum->getAsText(),
                'attr' => [
                    'placeholder' => 'Select one or more quotas',
                ],
                'multiple' => true,
                'autocomplete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'translation_domain' => 'work',
        ]);
    }
}
