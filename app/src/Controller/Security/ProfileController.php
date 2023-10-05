<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Company;
use App\Form\Handler\Security\ChangePasswordFormHandler;
use App\Security\Voter\CompanyVoter;
use App\Service\Security\SecurityManager;
use App\Service\User\UserUpdater;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/security/profile')]
class ProfileController extends AbstractAppController
{
    public function __construct(
        private readonly SecurityManager $securityManager,
        private readonly UserUpdater $userUpdater
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

        if ($formHandlerResponse->isSuccessful()) {
            $this->addFlash(self::FLASH_SUCCESS, new TranslatableMessage('Your password has been changed successfully', [], 'security'));
        }

        return $this->render('security/change_password.html.twig', [
            'form' => $formHandlerResponse->getForm(),
        ]);
    }

    #[Route('/switch-company/{company}', name: 'app_security_profile_switch_company')]
    public function switchCompany(Company $company): RedirectResponse
    {
        $this->denyAccessUnlessGranted(CompanyVoter::ALLOWED_TO_SWITCH, $company);
        $this->userUpdater->updateConnectedOn($this->securityManager->getConnectedUser(), $company);

        return $this->redirectToRoute('_welcome');
    }
}
