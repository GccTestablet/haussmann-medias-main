<?php

declare(strict_types=1);

namespace App\Form\Type\User;

use App\Entity\User;
use App\Form\Dto\User\UserFormDto;
use App\Form\Validator\Constraint\EmailExists;
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
                    new EmailExists($dto->isExists() ? $dto->getUser() : null),
                ],
            ])
        ;

        if (!$this->securityManager->hasRole(User::ROLE_SUPER_ADMIN)) {
            return;
        }

        $builder
            ->add('role', ChoiceType::class, [
                'placeholder' => 'Select a role',
                'choices' => [
                    'Super administrator' => User::ROLE_SUPER_ADMIN,
                    'Administrator' => User::ROLE_ADMIN,
                    'Supplier' => User::ROLE_SUPPLIER,
                    'Distributor' => User::ROLE_DISTRIBUTOR,
                ],
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
