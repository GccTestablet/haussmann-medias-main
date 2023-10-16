<?php

declare(strict_types=1);

namespace App\Form\Type\Contract;

use App\Entity\Work;
use App\Form\Dto\Contract\DistributionContractWorkFormDto;
use App\Repository\WorkRepository;
use App\Service\Work\WorkManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DistributionContractWorkFormType extends AbstractType
{
    public function __construct(
        private readonly WorkManager $workManager
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var DistributionContractWorkFormDto $dto */
        $dto = $builder->getData();
        $distributionContract = $dto->getDistributionContractWork()->getDistributionContract();
        $works = $this->workManager->findByDistributionContract($distributionContract);

        $builder
            ->add('work', EntityType::class, [
                'placeholder' => 'Select a work',
                'class' => Work::class,
                'choice_label' => 'name',
                'autocomplete' => true,
                'query_builder' => fn (WorkRepository $workRepository) => $workRepository->createQueryBuilder('w')
//                    ->where('w.id NOT IN (:works)')
//                    ->setParameter('works', $works)
                    ->orderBy('w.name', 'ASC'),
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
