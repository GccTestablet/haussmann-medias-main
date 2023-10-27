<?php

declare(strict_types=1);

namespace App\Form\Type\Common;

use App\Entity\Setting\BroadcastChannel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BroadcastChannelEntityField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'class' => BroadcastChannel::class,
                'query_builder' => fn ($repository) => $repository
                    ->createQueryBuilder('bc')->orderBy('bc.name', 'ASC'),
                'choice_label' => 'name',
                'autocomplete' => true,
            ])
        ;
    }

    public function getParent(): string
    {
        return EntityType::class;
    }
}
