<?php

declare(strict_types=1);

namespace App\Form\Type\Work;

use App\Entity\Work\Work;
use App\Enum\Work\WorkQuotaEnum;
use App\Form\Dto\Work\WorkFormDto;
use App\Form\Type\Shared\CurrencyType;
use App\Form\Validator\Constraint\UniqueEntityField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var WorkFormDto $dto */
        $dto = $builder->getData();

        $builder
            ->add('archived', CheckboxType::class, [
                'label' => 'Archived?',
                'required' => false,
                'translation_domain' => 'misc',
            ])
            ->add('internalId', TextType::class, [
                'label' => 'Internal Id',
                'disabled' => true,
                'constraints' => [
                    new UniqueEntityField(
                        entityClass: Work::class,
                        field: 'internalId',
                        origin: $dto->isExists() ? $dto->getWork() : null
                    ),
                ],
            ])
            ->add('imdbId', TextType::class, [
                'label' => 'Imdb id',
                'required' => false,
                'help' => 'IMDB Id starts with "tt" followed by numbers.',
            ])
            ->add('name', TextType::class)
            ->add('originalName', TextType::class)
            ->add('countries', CountryType::class, [
                'placeholder' => 'Select a country',
                'preferred_choices' => ['FR', 'US'],
                'multiple' => true,
                'autocomplete' => true,
                'translation_domain' => 'misc',
            ])
            ->add('quota', EnumType::class, [
                'placeholder' => 'Select a quota',
                'class' => WorkQuotaEnum::class,
                'choice_label' => fn (WorkQuotaEnum $originWorkEnum) => $originWorkEnum->getAsText(),
                'required' => true,
            ])
            ->add('year', NumberType::class, [
                'required' => false,
            ])
            ->add('duration', TextType::class, [
                'required' => false,
            ])
            ->add('minimumGuaranteedBeforeReversion', NumberType::class, [
                'required' => false,
            ])
            ->add('minimumCostOfTheTopBeforeReversion', NumberType::class, [
                'required' => false,
            ])
            ->add('currency', CurrencyType::class, [
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WorkFormDto::class,
            'translation_domain' => 'work',
        ]);
    }
}
