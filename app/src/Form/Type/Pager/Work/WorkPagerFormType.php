<?php

declare(strict_types=1);

namespace App\Form\Type\Pager\Work;

use App\Enum\Pager\ColumnEnum;
use App\Enum\Work\OriginWorkEnum;
use App\Form\Type\Common\CompanyEntityField;
use App\Form\Type\Pager\Shared\BasePagerFormType;
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
            ->add(ColumnEnum::NAME->value, TextType::class, [
                'label' => 'Title (French or original)',
            ])
            ->add(ColumnEnum::ACQUISITION_CONTRACT_NAME->value, TextType::class, [
                'label' => 'Acquisition contract name',
            ])
            ->add(ColumnEnum::BENEFICIARIES->value, CompanyEntityField::class, [
                'label' => 'Acquisition contract beneficiary',
                'attr' => [
                    'placeholder' => 'All beneficiaries',
                ],
                'multiple' => true,
            ])
            ->add(ColumnEnum::QUOTA->value, EnumType::class, [
                'label' => 'Quota',
                'class' => OriginWorkEnum::class,
                'choice_label' => fn (OriginWorkEnum $originWorkEnum) => $originWorkEnum->getAsText(),
                'placeholder' => 'All quotas',
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
