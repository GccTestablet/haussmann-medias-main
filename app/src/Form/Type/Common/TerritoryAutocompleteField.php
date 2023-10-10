<?php

declare(strict_types=1);

namespace App\Form\Type\Common;

use App\Entity\Territory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class TerritoryAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Territory::class,
            'placeholder' => 'Choose a Territory',
            'choice_label' => fn (Territory $territory) => $territory->getName(),
            'translation_domain' => 'setting',
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
