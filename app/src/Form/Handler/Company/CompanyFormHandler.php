<?php

declare(strict_types=1);

namespace App\Form\Handler\Company;

use App\Form\Dto\Company\CompanyFormDto;
use App\Form\DtoFactory\Company\CompanyFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Company\CompanyFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CompanyFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CompanyFormDtoFactory $companyFormDtoFactory,
    ) {}

    protected static string $form = CompanyFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof CompanyFormDto) {
            throw new UnexpectedTypeException($dto, CompanyFormDto::class);
        }

        $company = $dto->getCompany();
        if ($dto->isExists()) {
            $this->entityManager->refresh($company);
        }

        $this->companyFormDtoFactory->updateCompany($dto, $company);

        $this->entityManager->persist($company);
        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
