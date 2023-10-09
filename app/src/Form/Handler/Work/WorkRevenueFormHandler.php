<?php

declare(strict_types=1);

namespace App\Form\Handler\Work;

use App\Form\Dto\Work\WorkRevenueFormDto;
use App\Form\DtoFactory\Work\WorkRevenueFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Work\WorkRevenueFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class WorkRevenueFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WorkRevenueFormDtoFactory $workRevenueFormDtoFactory,
    ) {}

    protected static string $form = WorkRevenueFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof WorkRevenueFormDto) {
            throw new UnexpectedTypeException($dto, WorkRevenueFormDto::class);
        }

        $workRevenue = $dto->getWorkRevenue();
        if ($dto->isExists()) {
            $this->entityManager->refresh($workRevenue);
        }

        $this->workRevenueFormDtoFactory->updateEntity($dto, $workRevenue);

        $this->entityManager->persist($workRevenue);
        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
