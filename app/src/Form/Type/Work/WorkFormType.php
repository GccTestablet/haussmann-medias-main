<?php

declare(strict_types=1);

namespace App\Form\Type\Work;

use App\Form\Dto\Work\WorkFormDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('internalId', TextType::class, [
                'label' => 'Internal ID',
            ])
            ->add('name', TextType::class, [
                'label' => 'Name',
            ])
            ->add('originalName', TextType::class, [
                'label' => 'Original name',
            ])
            ->add('synopsis', TextType::class, [
                'label' => 'Synopsis',
                'required' => false,
            ])
            ->add('year', TextType::class, [
                'label' => 'Year',
                'required' => false,
            ])
            ->add('duration', TextType::class, [
                'label' => 'Duration',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WorkFormDto::class,
        ]);
    }
}
