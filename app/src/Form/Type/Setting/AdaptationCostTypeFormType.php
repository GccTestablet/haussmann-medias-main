<?php

declare(strict_types=1);

namespace App\Form\Type\Setting;

use App\Entity\Setting\AdaptationCostType;
use App\Form\Dto\Setting\AdaptationCostTypeFormDto;
use App\Form\Validator\Constraint\UniqueEntityField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdaptationCostTypeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var AdaptationCostTypeFormDto $dto */
        $dto = $builder->getData();

        $builder
            ->add('name', TextType::class, [
                'constraints' => new UniqueEntityField(
                    entityClass: AdaptationCostType::class,
                    field: 'name',
                    origin: $dto->isExists() ? $dto->getType() : null
                ),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdaptationCostTypeFormDto::class,
        ]);
    }
}
