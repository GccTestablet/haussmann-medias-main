<?php

declare(strict_types=1);

namespace App\Form\Handler\Contract;

use App\Form\Dto\Contract\DistributionContractWorkTerritoryFormDto;
use App\Form\DtoFactory\Contract\DistributionContractWorkTerritoryFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Contract\DistributionContractWorkTerritoryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DistributionContractWorkTerritoryFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DistributionContractWorkTerritoryFormDtoFactory $formDtoFactory,
    ) {}

    protected static string $form = DistributionContractWorkTerritoryFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof DistributionContractWorkTerritoryFormDto) {
            throw new UnexpectedTypeException($dto, DistributionContractWorkTerritoryFormDto::class);
        }

        $contractWork = $dto->getContractWork();
        $this->entityManager->refresh($contractWork);

        $this->formDtoFactory->updateEntity($contractWork, $dto->getTerritories());

        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
