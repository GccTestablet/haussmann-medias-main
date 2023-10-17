<?php

declare(strict_types=1);

namespace App\Form\Type\Work;

use App\Form\Dto\Work\WorkTerritoryFormDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkTerritoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var WorkTerritoryFormDto $dto */
        $dto = $builder->getData();
        foreach ($dto->getTerritories() as $key => $value) {
            $builder->add($key, CheckboxType::class, [
                'label' => false,
                'mapped' => false,
                'required' => false,
                'data' => $value,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WorkTerritoryFormDto::class,
        ]);
    }
}
