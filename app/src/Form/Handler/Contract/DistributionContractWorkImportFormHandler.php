<?php

declare(strict_types=1);

namespace App\Form\Handler\Contract;

use App\Form\Dto\Contract\DistributionContractWorkRevenueImportFormDto;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Contract\DistributionContractWorkRevenueImportFormType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DistributionContractWorkImportFormHandler extends AbstractFormHandler
{
    protected static string $form = DistributionContractWorkRevenueImportFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof DistributionContractWorkRevenueImportFormDto) {
            throw new UnexpectedTypeException($dto, DistributionContractWorkRevenueImportFormDto::class);
        }

        // TODO: Save file and import

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
