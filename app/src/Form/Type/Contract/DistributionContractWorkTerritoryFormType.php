<?php

declare(strict_types=1);

namespace App\Form\Type\Contract;

use App\Entity\Setting\BroadcastChannel;
use App\Entity\Setting\Territory;
use App\Form\Dto\Contract\DistributionContractWorkTerritoryFormDto;
use App\Form\Type\Common\BroadcastChannelAutocompleteField;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class DistributionContractWorkTerritoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);
        $builder
            ->add('territory', EntityType::class, [
                'class' => Territory::class,
                'choice_label' => 'name',
                'constraints' => new NotBlank(),
            ])
            ->addDependent('broadcastChannels', 'territory', function (DependentField $field, ?Territory $territory): void {
                $field->add(EntityType::class, [
                    'class' => BroadcastChannel::class,
                    'choice_label' => 'name',
                ]);
            })
//            ->add('broadcastChannels', BroadcastChannelAutocompleteField::class, [
//                'multiple' => true,
//                'constraints' => new NotBlank(),
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DistributionContractWorkTerritoryFormDto::class,
            'translation_domain' => 'contract',
        ]);
    }
}
