<?php

declare(strict_types=1);

namespace App\Form\Type\Contract;

use App\Entity\Company;
use App\Entity\Contract\AcquisitionContract;
use App\Enum\Common\FrequencyEnum;
use App\Form\Dto\Contract\AcquisitionContractFormDto;
use App\Form\Type\Shared\DateType;
use App\Form\Validator\Constraint\UniqueEntityField;
use App\Repository\CompanyRepository;
use App\Service\Contract\ContractFileHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AcquisitionContractFormType extends AbstractType
{
    public function __construct(
        private readonly ContractFileHelper $contractFileHelper
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var AcquisitionContractFormDto $dto */
        $dto = $builder->getData();

        $builder
            ->add('beneficiary', EntityType::class, [
                'class' => Company::class,
                'placeholder' => 'Select a beneficiary',
                'query_builder' => fn (CompanyRepository $repository) => $repository->createQueryBuilder('b')->orderBy('b.name'),
                'choice_label' => 'name',
            ])
            ->add('name', TextType::class, [
                'constraints' => new UniqueEntityField(
                    entityClass: AcquisitionContract::class,
                    field: 'name',
                    origin: $dto->isExists() ? $dto->getContract() : null
                ),
            ])
            ->add('signedAt', DateType::class, [
                'label' => 'Signed at',
            ])
            ->add('files', FileType::class, [
                'label' => 'Add more files',
                'required' => false,
                'multiple' => true,
                'attr' => [
                    'class' => 'custom-file-input',
                ],
                'help' => \implode('', $this->contractFileHelper->getFilesHelper($dto->getContract())),
                'help_html' => true,
            ])
            ->add('startsAt', DateType::class, [
                'label' => 'Rights starts at',
                'required' => false,
            ])
            ->add('endsAt', DateType::class, [
                'label' => 'Rights ends at',
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
            'data_class' => AcquisitionContractFormDto::class,
            'translation_domain' => 'contract',
        ]);
    }
}
