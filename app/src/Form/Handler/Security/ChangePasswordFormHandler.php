<?php

declare(strict_types=1);

namespace App\Form\Handler\Security;

use App\Form\Dto\Security\ChangePasswordFormDto;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Security\ChangePasswordFormType;
use App\Service\Security\SecurityManager;
use App\Service\Security\UserPasswordManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class ChangePasswordFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SecurityManager $securityManager,
        private readonly UserPasswordManager $userPasswordManager
    ) {}

    protected static string $form = ChangePasswordFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof ChangePasswordFormDto) {
            throw new \InvalidArgumentException();
        }

        $user = $this->securityManager->getConnectedUser();
        $this->userPasswordManager->updatePassword($user, $dto->getNewPassword());

        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
