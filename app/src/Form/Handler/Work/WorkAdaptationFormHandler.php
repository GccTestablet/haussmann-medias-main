<?php

declare(strict_types=1);

namespace App\Form\Handler\Work;

use App\Form\Dto\Work\WorkAdaptationFormDto;
use App\Form\DtoFactory\Work\WorkAdaptationFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Work\WorkAdaptationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class WorkAdaptationFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WorkAdaptationFormDtoFactory $workAdaptationFormDtoFactory,
    ) {}

    protected static string $form = WorkAdaptationFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof WorkAdaptationFormDto) {
            throw new UnexpectedTypeException($dto, WorkAdaptationFormDto::class);
        }

        $workAdaptation = $dto->getWorkAdaptation();
        if ($dto->isExists()) {
            $this->entityManager->refresh($workAdaptation);
        }

        $this->workAdaptationFormDtoFactory->updateEntity($dto, $workAdaptation);

        $this->entityManager->persist($workAdaptation);
        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
