<?php

declare(strict_types=1);

namespace App\Form\Type\Pager\Work;

use App\Entity\Company;
use App\Enum\Pager\ColumnEnum;
use App\Form\Type\Pager\Shared\BasePagerFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
                'label' => false,
                'attr' => [
                    'placeholder' => 'Internal Id',
                ],
                'row_attr' => [
                    'class' => 'col-sm-6 col-lg-3',
                ],
            ])
            ->add(ColumnEnum::NAME->value, TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Title (French or original)',
                ],
                'row_attr' => [
                    'class' => 'col-sm-6 col-lg-3',
                ],
            ])
            ->add(ColumnEnum::ACQUISITION_CONTRACT->value, TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Acquisition contract name',
                ],
                'row_attr' => [
                    'class' => 'col-sm-6 col-lg-3',
                ],
            ])
            ->add(ColumnEnum::BENEFICIARY->value, EntityType::class, [
                'label' => false,
                'class' => Company::class,
                'multiple' => true,
                'choice_label' => 'name',
                'attr' => [
                    'placeholder' => 'Acquisition contract beneficiary',
                ],
                'row_attr' => [
                    'class' => 'col-sm-6 col-lg-3',
                ],
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
