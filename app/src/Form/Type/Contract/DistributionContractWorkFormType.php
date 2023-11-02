<?php

declare(strict_types=1);

namespace App\Form\Type\Contract;

use App\Enum\Pager\ColumnEnum;
use App\Form\Dto\Contract\DistributionContractWorkFormDto;
use App\Form\Type\Common\WorkEntityField;
use App\Form\Type\Shared\CurrencyType;
use App\Form\Type\Shared\DateType;
use Doctrine\Common\Collections\ArrayCollection;
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
            ->add('work', WorkEntityField::class, [
                'required' => true,
                ...$dto->getWork() ? [ColumnEnum::INCLUDE => new ArrayCollection([$dto->getWork()])] : [],
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
