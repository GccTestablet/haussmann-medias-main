<?php

declare(strict_types=1);

namespace App\Form\Handler\Setting;

use App\Form\Dto\Setting\TerritoryFormDto;
use App\Form\DtoFactory\Setting\TerritoryFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Setting\TerritoryFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TerritoryFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TerritoryFormDtoFactory $territoryFormDtoFactory,
    ) {}

    protected static string $form = TerritoryFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof TerritoryFormDto) {
            throw new UnexpectedTypeException($dto, TerritoryFormDto::class);
        }

        $territory = $dto->getTerritory();
        if ($dto->isExists()) {
            $this->entityManager->refresh($territory);
        }

        $this->territoryFormDtoFactory->updateEntity($dto, $territory);

        $this->entityManager->persist($territory);
        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
