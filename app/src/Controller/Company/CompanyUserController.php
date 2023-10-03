<?php

declare(strict_types=1);

namespace App\Controller\Company;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Company;
use App\Entity\User;
use App\Entity\UserCompany;
use App\Form\DtoFactory\Company\CompanyUserFormDtoFactory;
use App\Form\Handler\Common\RemoveFormHandler;
use App\Form\Handler\Company\CompanyUserFormHandler;
use App\Service\Company\CompanyUserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/companies/{company}/users', requirements: ['company' => '\d+'])]
class CompanyUserController extends AbstractAppController
{
    public function __construct(
        private readonly CompanyUserFormDtoFactory $companyUserFormDtoFactory,
        private readonly CompanyUserManager $companyUserManager
    ) {}

    #[Route(path: '/add', name: 'app_company_user_add')]
    public function add(Request $request, CompanyUserFormHandler $companyUserFormHandler, Company $company): Response
    {
        $dto = $this->companyUserFormDtoFactory->create($company);

        $formHandlerResponse = $this->formHandlerManager->createAndHandle(
            $companyUserFormHandler,
            $request,
            $dto
        );

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_company_show', ['id' => $company->getId()]);
        }

        return $this->render('company/user/add.html.twig', [
            'title' => new TranslatableMessage('Add user to company %name%', ['%name%' => $company->getName()], 'company'),
            'form' => $form,
        ]);
    }

    #[Route(path: '/{user}/remove', name: 'app_company_user_remove', requirements: ['id' => '\d+'])]
    public function remove(Request $request, RemoveFormHandler $removeFormHandler, Company $company, User $user): Response
    {
        $userCompany = $this->companyUserManager->findByCompanyAndUser($company, $user);
        if (!$userCompany instanceof UserCompany) {
            throw $this->createNotFoundException(
                \sprintf('User %d is not in company %d', $user->getId(), $company->getId())
            );
        }

        $formHandlerResponse = $this->formHandlerManager->createAndHandle(
            $removeFormHandler,
            $request,
            $userCompany
        );

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_company_show', ['id' => $company->getId()]);
        }

        return $this->render('shared/common/remove.html.twig', [
            'title' => new TranslatableMessage('Remove user %user% from company %company%?', [
                '%user%' => $userCompany->getUser()->getFullName(),
                '%company%' => $company->getName(),
            ], 'company'),
            'form' => $form,
        ]);
    }
}
