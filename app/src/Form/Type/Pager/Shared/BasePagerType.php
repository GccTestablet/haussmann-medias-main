<?php

declare(strict_types=1);

namespace App\Form\Type\Pager\Shared;

use App\Enum\Pager\ColumnEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BasePagerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $pagerDefaultData = $options['pager_default_data'];

        $builder
            ->add(ColumnEnum::SORT->name, HiddenType::class, [
                'data' => \key($pagerDefaultData),
            ])
            ->add(ColumnEnum::DIRECTION->name, HiddenType::class, [
                'data' => \current($pagerDefaultData),
            ])
            ->add(ColumnEnum::PAGE->name, HiddenType::class, [
                'data' => 1,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'translation_domain' => 'pager',
                'csrf_protection' => false,
                'required' => false,
                'mapped' => false,
                'method' => Request::METHOD_GET,
                'allow_extra_fields' => true,
                'pager_default_data' => [],
            ])
        ;
    }
}
