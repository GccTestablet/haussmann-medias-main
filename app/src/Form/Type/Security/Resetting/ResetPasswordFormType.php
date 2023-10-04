<?php

declare(strict_types=1);

namespace App\Form\Type\Security\Resetting;

use App\Form\Dto\Security\Resetting\ResetPasswordFormDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
            'data_class' => ResetPasswordFormDto::class,
        ]);
    }
}
