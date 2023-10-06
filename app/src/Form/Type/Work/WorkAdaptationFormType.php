<?php

declare(strict_types=1);

namespace App\Form\Type\Work;

use App\Form\Dto\Work\WorkAdaptationFormDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkAdaptationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dubbingCost', NumberType::class, [
                'required' => false,
            ])
            ->add('manufacturingCost', NumberType::class, [
                'required' => false,
            ])
            ->add('mediaMatrixFileCost', NumberType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WorkAdaptationFormDto::class,
        ]);
    }
}
