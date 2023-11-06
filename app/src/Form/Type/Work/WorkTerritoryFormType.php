<?php

declare(strict_types=1);

namespace App\Form\Type\Work;

use App\Form\Dto\Work\WorkTerritoryFormDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkTerritoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('exclusives', CollectionType::class, [
                'label' => false,
                'entry_type' => CheckboxType::class,
                'entry_options' => [
                    'label' => false,
                    'required' => false,
                ],
            ])
            ->add('broadcastChannels', CollectionType::class, [
                'label' => false,
                'entry_type' => CheckboxType::class,
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
            'data_class' => WorkTerritoryFormDto::class,
            'allow_extra_fields' => true,
        ]);
    }
}
