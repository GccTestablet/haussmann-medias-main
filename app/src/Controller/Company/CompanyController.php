<?php

declare(strict_types=1);

namespace App\Controller\Company;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Company;
use App\Entity\User;
use App\Form\Dto\Company\CompanyFormDto;
use App\Form\DtoFactory\Company\CompanyFormDtoFactory;
use App\Form\Handler\Company\CompanyFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Security\Voter\CompanyVoter;
use App\Service\Company\CompanyManager;
use App\Service\Security\SecurityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[Route('/companies')]
class CompanyController extends AbstractAppController
{
    public function __construct(
        private readonly SecurityManager $securityManager,
        private readonly CompanyManager $companyManager,
        private readonly CompanyFormDtoFactory $companyFormDtoFactory,
        private readonly CompanyFormHandler $companyFormHandler,
    ) {}

    #[Route(name: 'app_company_index')]
    public function index(): Response
    {
        $user = $this->securityManager->getConnectedUser();
        $isSuperAdmin = $this->isGranted(User::ROLE_SUPER_ADMIN);

        $companies = $this->companyManager->findByUser($user, $isSuperAdmin);

        return $this->render('company/index.html.twig', [
            'companies' => $companies,
        ]);
    }

    #[Route(path: '/{id}', name: 'app_company_show', requirements: ['id' => '\d+'])]
    public function show(Company $company): Response
    {
        return $this->render('company/show.html.twig', [
            'company' => $company,
        ]);
    }

    #[Route('/add', name: 'app_company_add')]
    #[IsGranted(User::ROLE_SUPER_ADMIN)]
    public function add(Request $request): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, null);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var CompanyFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_company_show', ['id' => $dto->getCompany()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Add company', [], 'company'),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: 'app_company_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, Company $company): Response
    {
        $this->denyAccessUnlessGranted(CompanyVoter::COMPANY_ADMIN, $company);

        $formHandlerResponse = $this->getFormHandlerResponse($request, $company);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var CompanyFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_company_show', ['id' => $dto->getCompany()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update company %name%', ['%name%' => $company->getName()], 'company'),
            'form' => $form,
        ]);
    }

    private function getFormHandlerResponse(Request $request, ?Company $company): FormHandlerResponseInterface
    {
        $dto = $this->companyFormDtoFactory->create($company);

        return $this->formHandlerManager->createAndHandle(
            $this->companyFormHandler,
            $request,
            $dto
        );
    }
}
