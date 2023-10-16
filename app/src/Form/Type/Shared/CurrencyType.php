<?php

declare(strict_types=1);

namespace App\Form\Type\Shared;

use Symfony\Component\Form\Extension\Core\Type\CurrencyType as BaseCurrencyType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencyType extends BaseCurrencyType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'preferred_choices' => ['EUR', 'USD'],
            ])
        ;
    }
}
