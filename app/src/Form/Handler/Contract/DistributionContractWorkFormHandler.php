<?php

declare(strict_types=1);

namespace App\Form\Handler\Contract;

use App\Form\Dto\Contract\DistributionContractWorkFormDto;
use App\Form\DtoFactory\Contract\DistributionContractWorkFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Contract\DistributionContractWorkFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DistributionContractWorkFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DistributionContractWorkFormDtoFactory $formDtoFactory,
    ) {}

    protected static string $form = DistributionContractWorkFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof DistributionContractWorkFormDto) {
            throw new UnexpectedTypeException($dto, DistributionContractWorkFormDto::class);
        }

        $contractWork = $dto->getContractWork();
        if ($dto->isExists()) {
            $this->entityManager->refresh($contractWork);
        }

        $this->formDtoFactory->updateEntity($contractWork, $dto);

        $this->entityManager->persist($contractWork);
        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
