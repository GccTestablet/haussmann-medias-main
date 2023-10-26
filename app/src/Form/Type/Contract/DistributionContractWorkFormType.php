<?php

declare(strict_types=1);

namespace App\Form\Type\Contract;

use App\Entity\Work\Work;
use App\Form\Dto\Contract\DistributionContractWorkFormDto;
use App\Form\Type\Shared\CurrencyType;
use App\Form\Type\Shared\DateType;
use App\Repository\WorkRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DistributionContractWorkFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var DistributionContractWorkFormDto $dto */
        $dto = $builder->getData();
        $distributionContract = $dto->getContractWork()->getDistributionContract();

        $builder
            ->add('work', EntityType::class, [
                'class' => Work::class,
                'query_builder' => fn (WorkRepository $repository) => $repository
                    ->getAvailableWorksByDistributionContractQueryBuilder($distributionContract, $dto->getWork()),
                'choice_label' => 'name',
                'placeholder' => 'Select a work',
                'required' => true,
                'translation_domain' => 'work',
            ])
            ->add('startsAt', DateType::class, [
                'label' => 'Rights starts at',
                'required' => true,
                'translation_domain' => 'contract',
            ])
            ->add('endsAt', DateType::class, [
                'label' => 'Rights ends at',
                'required' => false,
                'translation_domain' => 'contract',
            ])
            ->add('amount', NumberType::class, [
                'required' => false,
                'translation_domain' => 'misc',
            ])
            ->add('currency', CurrencyType::class, [
                'required' => true,
                'translation_domain' => 'misc',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DistributionContractWorkFormDto::class,
            'translation_domain' => 'contract',
        ]);
    }
}
