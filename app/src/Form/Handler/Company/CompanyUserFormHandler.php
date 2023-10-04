<?php

declare(strict_types=1);

namespace App\Form\Handler\Company;

use App\Form\Dto\Company\CompanyUserFormDto;
use App\Form\DtoFactory\Company\CompanyUserFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Company\CompanyUserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CompanyUserFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CompanyUserFormDtoFactory $companyUserFormDtoFactory,
    ) {}

    protected static string $form = CompanyUserFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof CompanyUserFormDto) {
            throw new UnexpectedTypeException($dto, CompanyUserFormDto::class);
        }

        $company = $dto->getCompany();
        $this->entityManager->refresh($company);

        $this->companyUserFormDtoFactory->updateCompanyUser($dto, $company);

        $this->entityManager->persist($company);
        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
