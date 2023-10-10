<?php

declare(strict_types=1);

namespace App\Form\Handler\Setting;

use App\Form\Dto\Setting\BroadcastChannelFormDto;
use App\Form\DtoFactory\Setting\BroadcastChannelFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Setting\BroadcastChannelFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class BroadcastChannelFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BroadcastChannelFormDtoFactory $channelFormDtoFactory,
    ) {}

    protected static string $form = BroadcastChannelFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof BroadcastChannelFormDto) {
            throw new UnexpectedTypeException($dto, BroadcastChannelFormDto::class);
        }

        $channel = $dto->getChannel();
        if ($dto->isExists()) {
            $this->entityManager->refresh($channel);
        }

        $this->channelFormDtoFactory->updateEntity($dto, $channel);

        $this->entityManager->persist($channel);
        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
