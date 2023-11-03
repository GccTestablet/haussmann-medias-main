<?php

declare(strict_types=1);

namespace App\Form\Type\Pager\Work;

use App\Enum\Pager\ColumnEnum;
use App\Enum\Work\WorkQuotaEnum;
use App\Form\Type\Common\CompanyEntityField;
use App\Form\Type\Common\TerritoryEntityField;
use App\Form\Type\Pager\Shared\BasePagerFormType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatableMessage;

class WorkPagerFormType extends BasePagerFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add(ColumnEnum::INTERNAL_ID, TextType::class, [
                'label' => 'Internal Id',
            ])
            ->add(ColumnEnum::IMDB_ID, TextType::class, [
                'label' => 'Imdb id',
            ])
            ->add(ColumnEnum::NAME, TextType::class, [
                'label' => 'Title (French or original)',
            ])
            ->add(ColumnEnum::ACQUISITION_CONTRACT_NAME, TextType::class, [
                'label' => 'Acquisition contract name',
            ])
            ->add(ColumnEnum::ACQUISITION_CONTRACT_TERRITORIES, TerritoryEntityField::class, [
                'label' => 'Acquired territories',
                'attr' => [
                    'placeholder' => new TranslatableMessage('Select one or more', [], 'misc'),
                ],
                'multiple' => true,
                'translation_domain' => 'contract',
            ])
            ->add(ColumnEnum::BENEFICIARIES, CompanyEntityField::class, [
                'label' => 'Acquisition contract beneficiary',
                'attr' => [
                    'placeholder' => new TranslatableMessage('Select one or more', [], 'misc'),
                ],
                'multiple' => true,
            ])
            ->add(ColumnEnum::COUNTRIES, CountryType::class, [
                'attr' => [
                    'placeholder' => new TranslatableMessage('Select one or more', [], 'misc'),
                ],
                'translation_domain' => 'misc',
                'multiple' => true,
                'autocomplete' => true,
            ])
            ->add(ColumnEnum::QUOTAS, EnumType::class, [
                'class' => WorkQuotaEnum::class,
                'choice_label' => fn (WorkQuotaEnum $originWorkEnum) => $originWorkEnum->getAsText(),
                'attr' => [
                    'placeholder' => new TranslatableMessage('Select one or more', [], 'misc'),
                ],
                'multiple' => true,
                'autocomplete' => true,
            ])
            ->add(ColumnEnum::DISTRIBUTION_CONTRACT_TERRITORIES, TerritoryEntityField::class, [
                'label' => 'Distributed territories',
                'attr' => [
                    'placeholder' => new TranslatableMessage('Select one or more', [], 'misc'),
                ],
                'translation_domain' => 'contract',
                'multiple' => true,
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

        $resolver->setDefaults([
            'translation_domain' => 'work',
        ]);
    }
}
