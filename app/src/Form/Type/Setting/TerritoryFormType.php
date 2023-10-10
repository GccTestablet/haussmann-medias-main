<?php

declare(strict_types=1);

namespace App\Form\Type\Setting;

use App\Entity\Setting\Territory;
use App\Form\Dto\Setting\TerritoryFormDto;
use App\Form\Validator\Constraint\UniqueEntityField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TerritoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var TerritoryFormDto $dto */
        $dto = $builder->getData();

        $builder
            ->add('name', TextType::class, [
                'constraints' => new UniqueEntityField(
                    entityClass: Territory::class,
                    field: 'name',
                    origin: $dto->isExists() ? $dto->getTerritory() : null
                ),
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TerritoryFormDto::class,
        ]);
    }
}
