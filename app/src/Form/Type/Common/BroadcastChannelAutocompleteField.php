<?php

declare(strict_types=1);

namespace App\Form\Type\Common;

use App\Entity\Setting\BroadcastChannel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class BroadcastChannelAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => BroadcastChannel::class,
            'choice_label' => fn (BroadcastChannel $channel) => $channel->getName(),
            'placeholder' => 'Choose a broadcast channel',
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
