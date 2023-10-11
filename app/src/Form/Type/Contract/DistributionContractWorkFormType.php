<?php

declare(strict_types=1);

namespace App\Form\Type\Contract;

use App\Entity\Setting\BroadcastService;
use App\Form\Dto\Contract\DistributionContractWorkFormDto;
use App\Form\Type\Common\BroadcastChannelAutocompleteField;
use App\Form\Type\Common\TerritoryAutocompleteField;
use App\Form\Type\Common\WorkAutocompleteField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DistributionContractWorkFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('work', WorkAutocompleteField::class)
            ->add('territories', TerritoryAutocompleteField::class, [
                'required' => false,
                'multiple' => true,
            ])
            ->add('broadcastChannels', BroadcastChannelAutocompleteField::class, [
                'required' => false,
                'multiple' => true,
            ])
            ->add('broadcastServices', EntityType::class, [
                'placeholder' => 'Select a broadcast service',
                'class' => BroadcastService::class,
                'choice_label' => fn (BroadcastService $service): string => $service->getName(),
                'group_by' => fn (BroadcastService $service): string => $service->getBroadcastChannel()->getName(),
                'multiple' => true,
                'autocomplete' => true,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DistributionContractWorkFormDto::class,
            'translation_domain' => 'contract',
        ]);
    }
}
