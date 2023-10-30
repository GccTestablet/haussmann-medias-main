<?php

declare(strict_types=1);

namespace App\Form\Handler\Work;

use App\Form\Dto\Work\WorkTerritoryFormDto;
use App\Form\DtoFactory\Work\WorkTerritoryFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Work\WorkTerritoryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class WorkTerritoryFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WorkTerritoryFormDtoFactory $formDtoFactory,
    ) {}

    protected static string $form = WorkTerritoryFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof WorkTerritoryFormDto) {
            throw new UnexpectedTypeException($dto, WorkTerritoryFormDto::class);
        }

        $work = $dto->getWork();
        $this->entityManager->refresh($work);

        $this->formDtoFactory->updateEntity($work, $dto);

        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
