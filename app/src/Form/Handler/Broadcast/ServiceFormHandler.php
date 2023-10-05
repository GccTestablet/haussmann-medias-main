<?php

declare(strict_types=1);

namespace App\Form\Handler\Broadcast;

use App\Form\Dto\Broadcast\ServiceFormDto;
use App\Form\DtoFactory\Broadcast\ServiceFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Broadcast\ServiceFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ServiceFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ServiceFormDtoFactory $serviceFormDtoFactory,
    ) {}

    protected static string $form = ServiceFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof ServiceFormDto) {
            throw new UnexpectedTypeException($dto, ServiceFormDto::class);
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
