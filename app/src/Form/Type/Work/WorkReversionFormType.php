<?php

declare(strict_types=1);

namespace App\Form\Type\Work;

use App\Form\Dto\Work\WorkReversionFormDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkReversionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reversions', CollectionType::class, [
                'label' => false,
                'entry_type' => NumberType::class,
                'entry_options' => [
                    'label' => false,
                    'required' => false,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WorkReversionFormDto::class,
            'translation_domain' => 'work',
        ]);
    }
}
