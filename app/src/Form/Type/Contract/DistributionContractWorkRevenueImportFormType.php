<?php

declare(strict_types=1);

namespace App\Form\Type\Contract;

use App\Form\Dto\Contract\DistributionContractWorkRevenueImportFormDto;
use App\Form\Type\Shared\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

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
                'constraints' => new File(
                    mimeTypes: [
                        'text/csv', 'text/plain', 'application/csv', 'application/x-csv',
                        'text/comma-separated-values', 'text/x-comma-separated-values', 'text/tab-separated-values',
                    ]),
                'attr' => [
                    'accept' => '.csv',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DistributionContractWorkRevenueImportFormDto::class,
            'translation_domain' => 'contract',
        ]);
    }
}
