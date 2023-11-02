<?php

declare(strict_types=1);

namespace App\Form\Handler\Work;

use App\Form\Dto\Work\WorkReversionFormDto;
use App\Form\DtoFactory\Work\WorkReversionFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Work\WorkReversionFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class WorkReversionFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WorkReversionFormDtoFactory $workReversionFormDtoFactory,
    ) {}

    protected static string $form = WorkReversionFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof WorkReversionFormDto) {
            throw new UnexpectedTypeException($dto, WorkReversionFormDto::class);
        }

        $work = $dto->getWork();
        $this->entityManager->refresh($work);

        $this->workReversionFormDtoFactory->updateEntity($work, $dto);

        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
