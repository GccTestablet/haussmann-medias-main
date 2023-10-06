<?php

declare(strict_types=1);

namespace App\Form\Type\Work;

use App\Entity\BroadcastChannel;
use App\Form\Dto\Work\WorkReversionFormDto;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkReversionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('channel', EntityType::class, [
                'placeholder' => 'Select a channel',
                'required' => true,
                'class' => BroadcastChannel::class,
                'query_builder' => fn (EntityRepository $er) => $er->createQueryBuilder('bc')->orderBy('bc.name', 'ASC'),
                'choice_label' => 'name',
            ])
            ->add('percentageReversion', NumberType::class, [
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WorkReversionFormDto::class,
        ]);
    }
}
