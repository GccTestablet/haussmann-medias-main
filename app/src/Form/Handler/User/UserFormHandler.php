<?php

declare(strict_types=1);

namespace App\Form\Handler\User;

use App\Event\Mailer\Security\RegistrationEvent;
use App\Form\Dto\User\UserFormDto;
use App\Form\DtoFactory\User\UserFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\User\UserFormType;
use App\Service\Security\UserPasswordManager;
use App\Tools\Generator\PasswordGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UserFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly UserFormDtoFactory $userFormDtoFactory,
        private readonly UserPasswordManager $userPasswordManager
    ) {}

    protected static string $form = UserFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $plainPassword = PasswordGenerator::generate(10);

        $dto = $form->getData();
        if (!$dto instanceof UserFormDto) {
            throw new UnexpectedTypeException($dto, UserFormDto::class);
        }

        $user = $dto->getUser();
        if ($dto->isExists()) {
            $this->entityManager->refresh($user);
        }

        $this->userFormDtoFactory->updateUser($dto, $user);

        if (!$dto->isExists()) {
            $this->userPasswordManager->updatePassword($user, $plainPassword);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        if (!$dto->isExists()) {
            $event = new RegistrationEvent($user, $plainPassword);
            $this->eventDispatcher->dispatch($event);
        }

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
