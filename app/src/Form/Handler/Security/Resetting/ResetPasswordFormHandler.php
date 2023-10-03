<?php

declare(strict_types=1);

namespace App\Form\Handler\Security\Resetting;

use App\Form\Dto\Security\Resetting\ResetPasswordFormDto;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Security\Resetting\ResetPasswordFormType;
use App\Service\Security\UserPasswordManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ResetPasswordFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordManager $userPasswordManager
    ) {}

    protected static string $form = ResetPasswordFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof ResetPasswordFormDto) {
            throw new \InvalidArgumentException();
        }

        if (!$dto->getUser()) {
            throw new \LogicException('Missing user');
        }

        $this->userPasswordManager->updatePassword($dto->getUser(), $dto->getNewPassword());
        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
