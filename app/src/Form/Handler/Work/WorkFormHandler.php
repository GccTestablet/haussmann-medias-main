<?php

declare(strict_types=1);

namespace App\Form\Handler\Work;

use App\Form\Dto\Work\WorkFormDto;
use App\Form\DtoFactory\Work\WorkFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Work\WorkFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class WorkFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WorkFormDtoFactory $workFormDtoFactory,
    ) {}

    protected static string $form = WorkFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof WorkFormDto) {
            throw new UnexpectedTypeException($dto, WorkFormDto::class);
        }

        $work = $dto->getWork();
        if ($dto->isExists()) {
            $this->entityManager->refresh($work);
        }

        $this->workFormDtoFactory->updateEntity($dto, $work);

        $this->entityManager->persist($work);
        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
