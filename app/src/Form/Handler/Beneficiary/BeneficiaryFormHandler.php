<?php

declare(strict_types=1);

namespace App\Form\Handler\Beneficiary;

use App\Form\Dto\Beneficiary\BeneficiaryFormDto;
use App\Form\DtoFactory\Beneficiary\BeneficiaryFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Beneficiary\BeneficiaryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class BeneficiaryFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BeneficiaryFormDtoFactory $beneficiaryFormDtoFactory,
    ) {}

    protected static string $form = BeneficiaryFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof BeneficiaryFormDto) {
            throw new UnexpectedTypeException($dto, BeneficiaryFormDto::class);
        }

        $beneficiary = $dto->getBeneficiary();
        if ($dto->isExists()) {
            $this->entityManager->refresh($beneficiary);
        }

        $this->beneficiaryFormDtoFactory->updateEntity($dto, $beneficiary);

        $this->entityManager->persist($beneficiary);
        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
