<?php

declare(strict_types=1);

namespace App\Form\Type\Common;

use App\Entity\Work;
use App\Repository\WorkRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class WorkAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
//            ->setDefined(['excluded_works'])
//            ->setAllowedTypes('excluded_works', 'array')
            ->setDefaults([
                'excluded_works' => [],
                'class' => Work::class,
                'placeholder' => 'Select a work',
                'choice_label' => fn (Work $work) => $work->getName(),
                'translation_domain' => 'work',
                'query_builder' => fn (WorkRepository $workRepository) => $workRepository->createQueryBuilder('w')
                    ->orderBy('w.name', 'ASC'),
            ])
            ->setAllowedTypes('excluded_works', 'array')
        ;
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
