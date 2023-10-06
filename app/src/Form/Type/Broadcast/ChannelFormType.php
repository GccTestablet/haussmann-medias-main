<?php

declare(strict_types=1);

namespace App\Form\Type\Broadcast;

use App\Entity\BroadcastChannel;
use App\Form\Dto\Broadcast\ChannelFormDto;
use App\Form\Validator\Constraint\UniqueEntityField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChannelFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ChannelFormDto $dto */
        $dto = $builder->getData();

        $builder
            ->add('name', TextType::class, [
                'constraints' => new UniqueEntityField(
                    entityClass: BroadcastChannel::class,
                    field: 'name',
                    origin: $dto->isExists() ? $dto->getChannel() : null
                ),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ChannelFormDto::class,
        ]);
    }
}
