<?php

declare(strict_types=1);

namespace App\Form\Type\Company;

use App\Entity\Company;
use App\Enum\Common\FrequencyEnum;
use App\Enum\Company\CompanyTypeEnum;
use App\Form\Dto\Company\CompanyContractFormDto;
use App\Form\Type\Shared\DateType;
use App\Repository\CompanyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyContractFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var CompanyContractFormDto $dto */
        $dto = $builder->getData();

        $builder
            ->add('beneficiary', EntityType::class, [
                'class' => Company::class,
                'placeholder' => 'Select a beneficiary',
                'query_builder' => fn (CompanyRepository $repository) => $repository->createQueryBuilder('b')->orderBy('b.name'),
                'choice_label' => 'name',
                'help' => 'International Seller companies only',
            ])
            ->add('file', FileType::class, [
                'label' => 'Contract',
                'required' => !$dto->isExists(),
                'attr' => [
                    'class' => 'custom-file-input',
                ],
                'help' => $dto->getFile()?->getClientOriginalName(),
            ])
            ->add('signedAt', DateType::class, [
                'label' => 'Signed at',
            ])
            ->add('startsAt', DateType::class, [
                'label' => 'Starts at',
            ])
            ->add('endsAt', DateType::class, [
                'label' => 'Ends at',
                'required' => false,
            ])
            ->add('territories', TextareaType::class, [
                'required' => false,
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
            'data_class' => CompanyContractFormDto::class,
            'translation_domain' => 'contract',
        ]);
    }
}
