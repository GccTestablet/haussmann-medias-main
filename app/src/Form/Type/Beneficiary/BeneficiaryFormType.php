<?php

declare(strict_types=1);

namespace App\Form\Type\Beneficiary;

use App\Entity\Beneficiary;
use App\Form\Dto\Beneficiary\BeneficiaryFormDto;
use App\Form\Validator\Constraint\UniqueEntityField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BeneficiaryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var BeneficiaryFormDto $dto */
        $dto = $builder->getData();

        $builder
            ->add('name', TextType::class, [
                'constraints' => new UniqueEntityField(
                    entityClass: Beneficiary::class,
                    field: 'name',
                    origin: $dto->isExists() ? $dto->getBeneficiary() : null
                ),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BeneficiaryFormDto::class,
        ]);
    }
}
