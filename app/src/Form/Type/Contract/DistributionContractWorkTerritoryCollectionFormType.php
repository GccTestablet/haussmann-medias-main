<?php

declare(strict_types=1);

namespace App\Form\Type\Contract;

use App\Form\Dto\Contract\DistributionContractWorkTerritoryCollectionFormDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

class DistributionContractWorkTerritoryCollectionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('territories', LiveCollectionType::class, [
                'entry_type' => DistributionContractWorkTerritoryFormType::class,
                'entry_options' => ['label' => false],
                'label' => false,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DistributionContractWorkTerritoryCollectionFormDto::class,
            'translation_domain' => 'contract',
        ]);
    }
}
