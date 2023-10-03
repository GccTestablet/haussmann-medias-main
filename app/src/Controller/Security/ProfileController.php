<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Controller\Shared\AbstractAppController;
use App\Form\Handler\Security\ChangePasswordFormHandler;
use App\Service\Security\SecurityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/security/profile')]
class ProfileController extends AbstractAppController
{
    public function __construct(
        private readonly SecurityManager $securityManager
    ) {}

    #[Route(name: 'app_security_profile_index')]
    public function index(): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $this->securityManager->getConnectedUser(),
        ]);
    }

    #[Route('/change-password', name: 'app_security_profile_change_password')]
    public function changePassword(Request $request, ChangePasswordFormHandler $changePasswordFormHandler): Response
    {
        $formHandlerResponse = $this->formHandlerManager->createAndHandle(
            $changePasswordFormHandler,
            $request
        );

        $success = false;
        if ($formHandlerResponse->isSuccessful()) {
            $success = true;
        }

        return $this->render('security/change_password.html.twig', [
            'form' => $formHandlerResponse->getForm(),
            'success' => $success,
        ]);
    }
}
