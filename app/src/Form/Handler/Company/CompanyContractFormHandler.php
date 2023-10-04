<?php

declare(strict_types=1);

namespace App\Form\Handler\Company;

use App\Form\Dto\Company\CompanyContractFormDto;
use App\Form\DtoFactory\Company\CompanyContractFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Company\CompanyContractFormType;
use App\Tools\Manager\UploadFileManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CompanyContractFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UploadFileManager $uploadFileManager,
        private readonly CompanyContractFormDtoFactory $companyContractFormDtoFactory,
    ) {}

    protected static string $form = CompanyContractFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof CompanyContractFormDto) {
            throw new UnexpectedTypeException($dto, CompanyContractFormDto::class);
        }

        $contract = $dto->getContract();
        if ($dto->isExists()) {
            $this->entityManager->refresh($contract);
        }

        $this->companyContractFormDtoFactory->updateEntity($dto, $contract);

        if ($dto->getFile()) {
            $this->uploadFileManager->upload($dto->getFile(), $contract->getUploadDir(), $contract->getFileName());
        }

        $this->entityManager->persist($contract);
        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
