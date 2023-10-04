<?php

declare(strict_types=1);

namespace App\Form\Type\Company;

use App\Entity\Beneficiary;
use App\Form\Dto\Company\CompanyContractFormDto;
use App\Form\Type\Shared\DateType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
                'class' => Beneficiary::class,
                'placeholder' => 'Select beneficiary',
                'query_builder' => fn (EntityRepository $entityRepository) => $entityRepository->createQueryBuilder('b')
                        ->orderBy('b.name', 'ASC'),
                'choice_label' => 'name',
                'required' => true,
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
                'required' => true,
            ])
            ->add('startsAt', DateType::class, [
                'label' => 'Starts at',
                'required' => true,
            ])
            ->add('endsAt', DateType::class, [
                'label' => 'Ends at',
                'required' => true,
            ])
            ->add('territories', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CompanyContractFormDto::class,
        ]);
    }
}
