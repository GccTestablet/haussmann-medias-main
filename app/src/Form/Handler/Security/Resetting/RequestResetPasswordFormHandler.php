<?php

declare(strict_types=1);

namespace App\Form\Handler\Security\Resetting;

use App\Form\Dto\Security\Resetting\RequestResetPasswordFormDto;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Security\Resetting\RequestResetPasswordFormType;
use App\Service\Security\UserPasswordManager;
use App\Service\User\UserManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestResetPasswordFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly UserManager $userManager,
        private readonly UserPasswordManager $userPasswordManager
    ) {}

    protected static string $form = RequestResetPasswordFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof RequestResetPasswordFormDto) {
            throw new \InvalidArgumentException();
        }

        $user = $this->userManager->findByEmail($dto->getEmail());
        if (!$user) {
            return parent::onFormSubmitAndValid($request, $form, $options);
        }

        $this->userPasswordManager->requestResetPassword($user);

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
