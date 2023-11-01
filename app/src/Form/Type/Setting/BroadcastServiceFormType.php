<?php

declare(strict_types=1);

namespace App\Form\Type\Setting;

use App\Entity\Setting\BroadcastService;
use App\Form\Dto\Setting\BroadcastServiceFormDto;
use App\Form\Validator\Constraint\UniqueEntityField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BroadcastServiceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var BroadcastServiceFormDto $dto */
        $dto = $builder->getData();

        $builder
            ->add('archived', CheckboxType::class, [
                'label' => 'Archived?',
                'required' => false,
                'translation_domain' => 'misc',
            ])
            ->add('name', TextType::class, [
                'constraints' => new UniqueEntityField(
                    entityClass: BroadcastService::class,
                    field: 'name',
                    origin: $dto->isExists() ? $dto->getService() : null
                ),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BroadcastServiceFormDto::class,
        ]);
    }
}
