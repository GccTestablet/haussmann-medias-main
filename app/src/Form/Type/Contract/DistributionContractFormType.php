<?php

declare(strict_types=1);

namespace App\Form\Type\Contract;

use App\Entity\Company;
use App\Entity\Contract\DistributionContract;
use App\Enum\Common\FrequencyEnum;
use App\Enum\Contract\DistributionContractTypeEnum;
use App\Enum\Pager\ColumnEnum;
use App\Form\Dto\Contract\DistributionContractFormDto;
use App\Form\Type\Common\BroadcastChannelEntityField;
use App\Form\Type\Common\TerritoryEntityField;
use App\Form\Type\Shared\DateType;
use App\Form\Validator\Constraint\UniqueEntityField;
use App\Repository\CompanyRepository;
use App\Service\Contract\ContractFileHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DistributionContractFormType extends AbstractType
{
    public function __construct(
        private readonly ContractFileHelper $contractFileHelper
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var DistributionContractFormDto $dto */
        $dto = $builder->getData();

        $builder
            ->add('archived', CheckboxType::class, [
                'label' => 'Archived?',
                'required' => false,
                'translation_domain' => 'misc',
            ])
            ->add('company', EntityType::class, [
                'label' => 'Seller',
                'class' => Company::class,
                'choice_label' => 'name',
                'disabled' => true,
            ])
            ->add('distributor', EntityType::class, [
                'class' => Company::class,
                'placeholder' => 'Select a distributor',
                'query_builder' => fn (CompanyRepository $repository) => $repository->createQueryBuilder('b')->orderBy('b.name'),
                'choice_label' => 'name',
            ])
            ->add('name', TextType::class, [
                'constraints' => new UniqueEntityField(
                    entityClass: DistributionContract::class,
                    field: 'name',
                    origin: $dto->isExists() ? $dto->getContract() : null
                ),
            ])
            ->add('type', EnumType::class, [
                'class' => DistributionContractTypeEnum::class,
                'choice_label' => fn (DistributionContractTypeEnum $enum) => $enum->getAsText(),
                'placeholder' => 'Select a contract type',
            ])
            ->add('territories', TerritoryEntityField::class, [
                'multiple' => true,
                ColumnEnum::INCLUDE => $dto->getTerritories(),
            ])
            ->add('broadcastChannels', BroadcastChannelEntityField::class, [
                'multiple' => true,
                ColumnEnum::INCLUDE => $dto->getBroadcastChannels(),
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
            ->add('exclusivity', TextareaType::class, [
                'required' => false,
            ])
            ->add('commercialConditions', TextareaType::class, [
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
            'data_class' => DistributionContractFormDto::class,
            'translation_domain' => 'contract',
        ]);
    }
}
