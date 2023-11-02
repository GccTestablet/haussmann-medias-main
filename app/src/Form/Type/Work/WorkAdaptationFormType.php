<?php

declare(strict_types=1);

namespace App\Form\Type\Work;

use App\Enum\Pager\ColumnEnum;
use App\Form\Dto\Work\WorkAdaptationFormDto;
use App\Form\Type\Common\AdaptationCostTypeEntityField;
use App\Form\Type\Shared\CurrencyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkAdaptationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var WorkAdaptationFormDto $dto */
        $dto = $builder->getData();
        $builder
            ->add('type', AdaptationCostTypeEntityField::class, [
                'required' => true,
                ColumnEnum::TYPE => $dto->getType(),
            ])
            ->add('amount', NumberType::class, [
                'required' => true,
                'translation_domain' => 'misc',
            ])
            ->add('currency', CurrencyType::class, [
                'required' => true,
                'translation_domain' => 'misc',
            ])
            ->add('comment', TextareaType::class, [
                'required' => false,
                'translation_domain' => 'misc',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WorkAdaptationFormDto::class,
            'translation_domain' => 'work',
        ]);
    }
}
