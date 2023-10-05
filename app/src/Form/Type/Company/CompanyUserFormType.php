<?php

declare(strict_types=1);

namespace App\Form\Type\Company;

use App\Entity\User;
use App\Enum\User\UserCompanyPermissionEnum;
use App\Form\Dto\Company\CompanyUserFormDto;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var CompanyUserFormDto $dto */
        $dto = $builder->getData();

        $builder
            ->add('user', EntityType::class, [
                'class' => User::class,
                'placeholder' => 'Select user',
                'query_builder' => fn (UserRepository $userRepository) => $userRepository->findUsersNotInCompanyQueryBuilder($dto->getCompany()),
                'choice_label' => 'fullName',
            ])
            ->add('permission', EnumType::class, [
                'class' => UserCompanyPermissionEnum::class,
                'choice_label' => fn (UserCompanyPermissionEnum $enum) => $enum->getAsText(),
                'choice_value' => fn (?UserCompanyPermissionEnum $enum) => $enum?->value,
                'placeholder' => 'Select permission',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompanyUserFormDto::class,
        ]);
    }
}
