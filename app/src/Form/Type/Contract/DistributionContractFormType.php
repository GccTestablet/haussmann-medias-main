<?php

declare(strict_types=1);

namespace App\Form\Type\Contract;

use App\Entity\Company;
use App\Enum\Common\FrequencyEnum;
use App\Enum\Contract\DistributionContractTypeEnum;
use App\Form\Dto\Contract\DistributionContractFormDto;
use App\Form\Type\Shared\DateType;
use App\Repository\CompanyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DistributionContractFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var DistributionContractFormDto $dto */
        $dto = $builder->getData();

        $builder
            ->add('distributor', EntityType::class, [
                'class' => Company::class,
                'placeholder' => 'Select a distributor',
                'query_builder' => fn (CompanyRepository $repository) => $repository->createQueryBuilder('b')->orderBy('b.name'),
                'choice_label' => 'name',
            ])
            ->add('type', EnumType::class, [
                'class' => DistributionContractTypeEnum::class,
                'choice_label' => fn (DistributionContractTypeEnum $enum) => $enum->getAsText(),
                'placeholder' => 'Select a contract type',
            ])
            ->add('file', FileType::class, [
                'label' => 'Contract',
                'required' => false,
                'attr' => [
                    'class' => 'custom-file-input',
                ],
                'help' => $dto->getFile()?->getClientOriginalName(),
            ])
            ->add('startsAt', DateType::class, [
                'label' => 'Rights starts at',
            ])
            ->add('endsAt', DateType::class, [
                'label' => 'Rights ends at',
                'required' => false,
            ])
            ->add('exclusivity', TextareaType::class, [
                'required' => false,
            ])
            ->add('amount', MoneyType::class, [
                'required' => false,
                'currency' => 'EUR',
            ])
            ->add('reportFrequency', EnumType::class, [
                'placeholder' => 'Select report frequency',
                'class' => FrequencyEnum::class,
                'choice_label' => fn (FrequencyEnum $frequencyEnum) => $frequencyEnum->getAsText(),
                'choice_translation_domain' => 'misc',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DistributionContractFormDto::class,
            'translation_domain' => 'contract',
        ]);
    }
}
