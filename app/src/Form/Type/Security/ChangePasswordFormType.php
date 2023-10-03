<?php

declare(strict_types=1);

namespace App\Form\Type\Security;

use App\Form\Dto\Security\ChangePasswordFormDto;
use App\Form\Type\Security\Resetting\ResetPasswordFormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

class ChangePasswordFormType extends ResetPasswordFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('old_password', PasswordType::class, [
                'label' => 'Old password',
                'required' => true,
                'constraints' => new SecurityAssert\UserPassword(),
            ])
        ;

        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChangePasswordFormDto::class,
        ]);
    }
}
