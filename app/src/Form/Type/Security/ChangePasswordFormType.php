<?php

declare(strict_types=1);

namespace App\Form\Type\Security;

use App\Form\Dto\Security\ChangePasswordFormDto;
use App\Form\Type\Security\Resetting\ResetPasswordFormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

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
        $builder
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'New password'],
                'second_options' => ['label' => 'Confirm password'],
                'constraints' => [
                    new Assert\Length(min: 10, minMessage: 'Password must contain at least {{ limit }} chars long'),
                    new Assert\Regex(pattern: '/[A-Z]/', message: 'Password must contain at least 1 upper case'),
                    new Assert\Regex(pattern: '/[a-z]/', message: 'Password must contain at least 1 lower case'),
                    new Assert\Regex(pattern: '/[0-9]/', message: 'Password must contain at least 1 number'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChangePasswordFormDto::class,
        ]);
    }
}
