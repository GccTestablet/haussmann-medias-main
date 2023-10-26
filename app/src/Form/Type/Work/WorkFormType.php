<?php

declare(strict_types=1);

namespace App\Form\Type\Work;

use App\Entity\Work\Work;
use App\Form\Dto\Work\WorkFormDto;
use App\Form\Type\Shared\CurrencyType;
use App\Form\Validator\Constraint\UniqueEntityField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
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
            ->add('name', TextType::class, [
                'constraints' => [
                    new UniqueEntityField(
                        entityClass: Work::class,
                        field: 'name',
                        origin: $dto->isExists() ? $dto->getWork() : null
                    ),
                ],
            ])
            ->add('originalName', TextType::class, [
                'constraints' => [
                    new UniqueEntityField(
                        entityClass: Work::class,
                        field: 'originalName',
                        origin: $dto->isExists() ? $dto->getWork() : null
                    ),
                ],
            ])
            ->add('country', CountryType::class, [
                'preferred_choices' => ['FR', 'US'],
                'autocomplete' => true,
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
            ->add('year', NumberType::class, [
                'required' => false,
            ])
            ->add('duration', TextType::class, [
                'required' => false,
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
