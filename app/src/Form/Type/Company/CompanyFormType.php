<?php

declare(strict_types=1);

namespace App\Form\Type\Company;

use App\Entity\Company;
use App\Enum\Company\CompanyTypeEnum;
use App\Form\Dto\Company\CompanyFormDto;
use App\Form\Validator\Constraint\UniqueEntityField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var CompanyFormDto $dto */
        $dto = $builder->getData();

        $builder
            ->add('name', TextType::class, [
                'constraints' => new UniqueEntityField(
                    entityClass: Company::class,
                    field: 'name',
                    origin: $dto->isExists() ? $dto->getCompany() : null
                ),
            ])
            ->add('type', EnumType::class, [
                'placeholder' => 'Select company type',
                'class' => CompanyTypeEnum::class,
                'choice_label' => fn (CompanyTypeEnum $enum) => $enum->getAsText(),
                'choice_value' => fn (?CompanyTypeEnum $enum) => $enum?->value,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompanyFormDto::class,
            'translation_domain' => 'company',
        ]);
    }
}
