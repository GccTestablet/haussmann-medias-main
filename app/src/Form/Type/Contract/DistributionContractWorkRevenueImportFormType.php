<?php

declare(strict_types=1);

namespace App\Form\Type\Contract;

use App\Form\Dto\Contract\DistributionContractWorkRevenueImportFormDto;
use App\Form\Type\Shared\CurrencyType;
use App\Form\Type\Shared\DateType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DistributionContractWorkRevenueImportFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startsAt', DateType::class, [
                'required' => true,
            ])
            ->add('endsAt', DateType::class, [
                'required' => true,
            ])
            ->add('file', FileType::class, [
                'required' => true,
                'attr' => [
                    'accept' => '.csv',
                ],
            ])
            ->add('currency', CurrencyType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DistributionContractWorkRevenueImportFormDto::class,
            'translation_domain' => 'contract',
        ]);
    }
}
