<?php

declare(strict_types=1);

namespace App\Form\Type\Shared;

use Symfony\Component\Form\Extension\Core\Type\DateType as BaseDateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateType extends BaseDateType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'html5' => false,
                'attr' => [
                    'autocomplete' => 'off',
                    'data-controller' => 'widgets--date-picker',
                ],
            ])
        ;
    }
}
