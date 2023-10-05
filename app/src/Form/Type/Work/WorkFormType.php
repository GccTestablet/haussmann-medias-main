<?php

declare(strict_types=1);

namespace App\Form\Type\Work;

use App\Enum\Work\OriginWorkEnum;
use App\Form\Dto\Work\WorkFormDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('internalId', TextType::class)
            ->add('imdbId', TextType::class, [
                'required' => false,
                'help' => 'IMDB ID is a 7 digit number prefixed with "tt".',
            ])
            ->add('name', TextType::class)
            ->add('originalName', TextType::class)
            ->add('year', NumberType::class, [
                'required' => false,
                'help' => 'Year of release.',
            ])
            ->add('duration', TextType::class, [
                'required' => false,
            ])
            ->add('origin', EnumType::class, [
                'class' => OriginWorkEnum::class,
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
