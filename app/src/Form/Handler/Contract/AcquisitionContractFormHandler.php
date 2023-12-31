<?php

declare(strict_types=1);

namespace App\Form\Handler\Contract;

use App\Form\Dto\Contract\AcquisitionContractFormDto;
use App\Form\DtoFactory\Contract\AcquisitionContractFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Contract\AcquisitionContractFormType;
use App\Tools\Manager\UploadFileManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AcquisitionContractFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UploadFileManager $uploadFileManager,
        private readonly AcquisitionContractFormDtoFactory $companyContractFormDtoFactory,
    ) {}

    protected static string $form = AcquisitionContractFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof AcquisitionContractFormDto) {
            throw new UnexpectedTypeException($dto, AcquisitionContractFormDto::class);
        }

        $contract = $dto->getContract();
        if ($dto->isExists()) {
            $this->entityManager->refresh($contract);
        }

        try {
            $this->companyContractFormDtoFactory->updateEntity($dto, $contract);

            $this->entityManager->persist($contract);
            $this->entityManager->flush();

            foreach ($contract->getContractFiles() as $contractFile) {
                if (!$contractFile->getFile()) {
                    continue;
                }

                $this->uploadFileManager->upload($contractFile->getFile(), $contractFile->getUploadDir(), $contractFile->getFileName());
            }
        } catch (\Exception) {
        }

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
