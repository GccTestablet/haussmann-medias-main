<?php

declare(strict_types=1);

namespace App\Form\Handler\Contract;

use App\Form\Dto\Contract\DistributionContractFormDto;
use App\Form\DtoFactory\Contract\DistributionContractFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Contract\DistributionContractFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DistributionContractFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DistributionContractFormDtoFactory $distributionContractFormDtoFactory,
    ) {}

    protected static string $form = DistributionContractFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof DistributionContractFormDto) {
            throw new UnexpectedTypeException($dto, DistributionContractFormDto::class);
        }

        $contract = $dto->getContract();
        if ($dto->isExists()) {
            $this->entityManager->refresh($contract);
        }

        $this->distributionContractFormDtoFactory->updateEntity($dto, $contract);

        $this->entityManager->persist($contract);
        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
