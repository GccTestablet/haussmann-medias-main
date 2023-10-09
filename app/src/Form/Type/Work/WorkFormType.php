<?php

declare(strict_types=1);

namespace App\Form\Type\Work;

use App\Entity\Work;
use App\Enum\Work\OriginWorkEnum;
use App\Form\Dto\Work\WorkFormDto;
use App\Form\Validator\Constraint\UniqueEntityField;
use Symfony\Component\Form\AbstractType;
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
            ->add('internalId', TextType::class, [
                'constraints' => [
                    new UniqueEntityField(
                        entityClass: Work::class,
                        field: 'internalId',
                        origin: $dto->isExists() ? $dto->getWork() : null
                    ),
                ],
            ])
            ->add('imdbId', TextType::class, [
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
            ->add('origin', EnumType::class, [
                'class' => OriginWorkEnum::class,
                'choice_label' => fn (OriginWorkEnum $enum) => $enum->getAsText(),
                'choice_translation_domain' => 'work',
            ])
            ->add('minimumGuaranteedBeforeReversion', NumberType::class, [
                'required' => false,
            ])
            ->add('minimumCostOfTheTopBeforeReversion', NumberType::class, [
                'required' => false,
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
