<?php

declare(strict_types=1);

namespace App\Form\Handler\Setting;

use App\Form\Dto\Setting\AdaptationCostTypeFormDto;
use App\Form\DtoFactory\Setting\AdaptationCostTypeFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Setting\AdaptationCostTypeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class AdaptationCostTypeFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AdaptationCostTypeFormDtoFactory $adaptationCostTypeFormDtoFactory,
    ) {}

    protected static string $form = AdaptationCostTypeFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof AdaptationCostTypeFormDto) {
            throw new UnexpectedTypeException($dto, AdaptationCostTypeFormDto::class);
        }

        $type = $dto->getType();
        if ($dto->isExists()) {
            $this->entityManager->refresh($type);
        }

        $this->adaptationCostTypeFormDtoFactory->updateEntity($dto, $type);

        $this->entityManager->persist($type);
        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
