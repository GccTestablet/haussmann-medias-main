<?php

declare(strict_types=1);

namespace App\Form\Type\User;

use App\Entity\User;
use App\Form\Dto\User\UserFormDto;
use App\Form\Validator\Constraint\UniqueEntityField;
use App\Service\Security\SecurityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function __construct(
        private readonly SecurityManager $securityManager
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var UserFormDto $dto */
        $dto = $builder->getData();

        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new UniqueEntityField(
                        entityClass: User::class,
                        field: 'email',
                        origin: $dto->isExists() ? $dto->getUser() : null
                    ),
                ],
            ])
        ;

        if (!$this->securityManager->hasRole(User::ROLE_SUPER_ADMIN)) {
            return;
        }

        $builder
            ->add('role', ChoiceType::class, [
                'placeholder' => 'Select if you want to assign a specific role to the user',
                'required' => false,
                'choices' => [
                    'Super administrator' => User::ROLE_SUPER_ADMIN,
                    'Administrator' => User::ROLE_ADMIN,
                ],
                'help' => 'By default, the user will be assigned the role of "User"',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserFormDto::class,
        ]);
    }
}
