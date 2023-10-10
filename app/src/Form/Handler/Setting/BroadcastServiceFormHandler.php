<?php

declare(strict_types=1);

namespace App\Form\Handler\Setting;

use App\Form\Dto\Setting\BroadcastServiceFormDto;
use App\Form\DtoFactory\Setting\BroadcastServiceFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Setting\BroadcastServiceFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class BroadcastServiceFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BroadcastServiceFormDtoFactory $serviceFormDtoFactory,
    ) {}

    protected static string $form = BroadcastServiceFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof BroadcastServiceFormDto) {
            throw new UnexpectedTypeException($dto, BroadcastServiceFormDto::class);
        }

        $service = $dto->getService();
        if ($dto->isExists()) {
            $this->entityManager->refresh($service);
        }

        $this->serviceFormDtoFactory->updateEntity($dto, $service);

        $this->entityManager->persist($service);
        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
