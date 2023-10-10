<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Company;
use App\Form\Handler\Security\ChangePasswordFormHandler;
use App\Security\Voter\CompanyVoter;
use App\Service\Security\SecurityManager;
use App\Service\User\UserUpdater;
use App\Service\Work\WorkManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/profile')]
class ProfileController extends AbstractAppController
{
    public function __construct(
        private readonly SecurityManager $securityManager,
        private readonly UserUpdater $userUpdater,
        private readonly WorkManager $workManager
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

    #[Route('/switch-company/{company}', name: 'app_security_profile_switch_company', requirements: ['company' => '\d+'])]
    public function switchCompany(Company $company): RedirectResponse
    {
        $this->denyAccessUnlessGranted(CompanyVoter::ALLOWED_TO_SWITCH, $company);
        $this->userUpdater->updateConnectedOn($this->securityManager->getConnectedUser(), $company);

        return $this->redirectToRoute('_welcome');
    }

    #[Route('/company', name: 'app_security_profile_company')]
    public function company(): Response
    {
        $user = $this->securityManager->getConnectedUser();

        return $this->render('company/show.html.twig', [
            'company' => $user->getConnectedOn(),
        ]);
    }

    #[Route('/works', name: 'app_security_profile_works')]
    public function works(): Response
    {
        $user = $this->securityManager->getConnectedUser();

        return $this->render('work/index.html.twig', [
            'works' => $this->workManager->findByCompany($user->getConnectedOn()),
        ]);
    }

    #[Route('/acquisition-contracts', name: 'app_security_profile_acquisition_contracts')]
    public function acquisitionContracts(): Response
    {
        $user = $this->securityManager->getConnectedUser();

        return $this->render('acquisition_contract/index.html.twig', [
            'company' => $user->getConnectedOn(),
            'contracts' => $user->getConnectedOn()->getAcquisitionContracts(),
        ]);
    }
}
