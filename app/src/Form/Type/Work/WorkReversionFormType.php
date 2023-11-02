<?php

declare(strict_types=1);

namespace App\Form\Type\Work;

use App\Entity\Setting\BroadcastChannel;
use App\Form\Dto\Work\WorkReversionFormDto;
use App\Form\Type\Common\BroadcastChannelEntityField;
use App\Repository\Broadcast\BroadcastChannelRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WorkReversionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('reversions', CollectionType::class, [
                'label' => false,
                'entry_type' => NumberType::class,
                'entry_options' => [
                    'label' => false,
                    'required' => false,
                ],
            ])
        ;
        //        /** @var WorkReversionFormDto $dto */
        //        $dto = $builder->getData();
        //
        //        $builder
        //            ->add('channel', BroadcastChannelEntityField::class, [
        // //                'placeholder' => 'Select a channel',
        //                'required' => true,
        // //                'class' => BroadcastChannel::class,
        // //                'query_builder' => fn (BroadcastChannelRepository $repository) => $repository->getAvailableChannelByWorkQueryBuilder(
        // //                    $dto->getWorkReversion()->getWork(),
        // //                    $dto->getChannel()
        // //                ),
        // //                'choice_label' => 'name',
        // //                'translation_domain' => 'setting',
        //            ])
        //            ->add('percentageReversion', NumberType::class, [
        //                'required' => true,
        //            ])
        //        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WorkReversionFormDto::class,
            'translation_domain' => 'work',
        ]);
    }
}
