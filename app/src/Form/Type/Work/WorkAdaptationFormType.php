<?php

declare(strict_types=1);

namespace App\Form\Type\Work;

use App\Entity\Setting\AdaptationCostType;
use App\Form\Dto\Work\WorkAdaptationFormDto;
use App\Form\Type\Shared\CurrencyType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkAdaptationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', EntityType::class, [
                'placeholder' => 'Select a cost type',
                'class' => AdaptationCostType::class,
                'choice_label' => fn (AdaptationCostType $type) => $type->getName(),
                'required' => true,
                'autocomplete' => true,
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
