<?php

declare(strict_types=1);

namespace App\Form\Type\Admin;

use App\Entity\User;
use App\Form\Dto\Admin\RegisterFormDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('email', EmailType::class)
            ->add('role', ChoiceType::class, [
                'placeholder' => 'Select a role',
                'choices' => [
                    'Admin' => User::ROLE_ADMIN,
                    'Supplier' => User::ROLE_SUPPLIER,
                    'Distributor' => User::ROLE_DISTRIBUTOR,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RegisterFormDto::class,
        ]);
    }
}
