<?php

declare(strict_types=1);

namespace App\Form\Type\Shared;

use App\Form\Transformer\DateRangeTypeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateRangeType extends AbstractType
{
    public function __construct(
        private readonly DateRangeTypeTransformer $transformer,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'attr' => [
                    'autocomplete' => 'off',
                    'data-controller' => 'widgets--date-range-picker',
                ],
                'help' => 'Date between [...] and [...].',
                'translation_domain' => 'misc',
            ])
        ;
    }

    public function getParent(): string
    {
        return TextType::class;
    }
}
