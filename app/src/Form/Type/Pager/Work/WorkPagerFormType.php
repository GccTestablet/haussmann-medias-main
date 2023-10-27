<?php

declare(strict_types=1);

namespace App\Form\Type\Pager\Work;

use App\Enum\Pager\ColumnEnum;
use App\Form\Type\Common\CompanyEntityField;
use App\Form\Type\Pager\Shared\BasePagerFormType;
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
                'row_attr' => [
                    'class' => 'col-sm-6 col-lg-3',
                ],
            ])
            ->add(ColumnEnum::NAME->value, TextType::class, [
                'label' => 'Title (French or original)',
                'row_attr' => [
                    'class' => 'col-sm-6 col-lg-3',
                ],
            ])
            ->add(ColumnEnum::ACQUISITION_CONTRACT->value, TextType::class, [
                'label' => 'Acquisition contract name',
                'row_attr' => [
                    'class' => 'col-sm-6 col-lg-3',
                ],
            ])
            ->add(ColumnEnum::BENEFICIARIES->value, CompanyEntityField::class, [
                'label' => 'Acquisition contract beneficiary',
                'multiple' => true,
                'row_attr' => [
                    'class' => 'col-sm-6 col-lg-3',
                ],
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
