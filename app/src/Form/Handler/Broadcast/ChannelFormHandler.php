<?php

declare(strict_types=1);

namespace App\Form\Handler\Broadcast;

use App\Form\Dto\Broadcast\ChannelFormDto;
use App\Form\DtoFactory\Broadcast\ChannelFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Broadcast\ChannelFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ChannelFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ChannelFormDtoFactory $channelFormDtoFactory,
    ) {}

    protected static string $form = ChannelFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof ChannelFormDto) {
            throw new UnexpectedTypeException($dto, ChannelFormDto::class);
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
