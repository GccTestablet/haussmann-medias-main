<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Controller\Shared\AbstractAppController;
use App\Form\DtoFactory\Security\Resetting\ResetPasswordFormDtoFactory;
use App\Form\Handler\Security\Resetting\RequestResetPasswordFormHandler;
use App\Form\Handler\Security\Resetting\ResetPasswordFormHandler;
use App\Service\Security\UserPasswordManager;
use App\Service\User\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractAppController
{
    public function __construct(
        private readonly UserManager $userManager,
        private readonly ResetPasswordFormDtoFactory $resetPasswordFormDtoFactory
    ) {}

    #[Route(path: '/request', name: 'app_security_resetting_request')]
    public function request(Request $request, RequestResetPasswordFormHandler $resetPasswordFormHandler): Response
    {
        $formHandlerResponse = $this->formHandlerManager->createAndHandle(
            $resetPasswordFormHandler,
            $request
        );

        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_security_resetting_request_sent');
        }

        return $this->render('security/resetting/request.html.twig', [
            'form' => $formHandlerResponse->getForm(),
        ]);
    }

    #[Route(path: '/request/sent', name: 'app_security_resetting_request_sent')]
    public function requestSent(): Response
    {
        return $this->render('security/resetting/request_sent.html.twig', [
            'tokenLifetime' => UserPasswordManager::RESET_PASSWORD_TTL / 60,
        ]);
    }

    #[Route(path: '/reset/{confirmationToken}', name: 'app_security_resetting_reset_password')]
    public function resetPassword(Request $request, ResetPasswordFormHandler $resetPasswordFormHandler, string $confirmationToken): Response
    {
        $user = $this->userManager->findByPasswordResetToken($confirmationToken);
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $dto = $this->resetPasswordFormDtoFactory->create($user);

        $formHandlerResponse = $this->formHandlerManager->createAndHandle(
            $resetPasswordFormHandler,
            $request,
            $dto,
        );
        if ($formHandlerResponse->isSuccessful()) {
            $this->addFlash(parent::FLASH_SUCCESS, 'Votre mot de passe a été modifié');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/resetting/reset_password.html.twig', [
            'form' => $formHandlerResponse->getForm(),
        ]);
    }
}
