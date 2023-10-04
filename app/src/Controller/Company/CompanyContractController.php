<?php

declare(strict_types=1);

namespace App\Controller\Company;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Company;
use App\Entity\Contract;
use App\Entity\User;
use App\Form\DtoFactory\Company\CompanyContractFormDtoFactory;
use App\Form\Handler\Common\RemoveFormHandler;
use App\Form\Handler\Company\CompanyContractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Security\Voter\CompanyVoter;
use App\Tools\Manager\UploadFileManager;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[Route('/companies/{company}/contracts')]
class CompanyContractController extends AbstractAppController
{
    public function __construct(
        private readonly UploadFileManager $uploadFileManager,
        private readonly CompanyContractFormHandler $companyContractFormHandler,
        private readonly CompanyContractFormDtoFactory $companyContractFormDtoFactory,
    ) {}

    #[Route(path: '/{id}', name: 'app_company_contract_show', requirements: ['id' => '\d+'])]
    public function show(Contract $contract): Response
    {
        return $this->render('company/contract/show.html.twig', [
            'contract' => $contract,
        ]);
    }

    #[Route(path: '/add', name: 'app_company_contract_add')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function add(Request $request, Company $company): Response
    {
        $this->denyAccessUnlessGranted(CompanyVoter::COMPANY_ADMIN, $company);

        $formHandlerResponse = $this->getFormHandlerResponse($request, $company, null);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_company_show', ['id' => $company->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Add contract to company %name%', ['%name%' => $company->getName()], 'company'),
            'form' => $form,
        ]);
    }

    #[Route(path: '/{id}/update', name: 'app_company_contract_update', requirements: ['id' => '\d+'])]
    #[IsGranted(User::ROLE_ADMIN)]
    public function update(Request $request, Contract $contract): Response
    {
        $company = $contract->getCompany();
        $this->denyAccessUnlessGranted(CompanyVoter::COMPANY_ADMIN, $company);

        $formHandlerResponse = $this->getFormHandlerResponse($request, $company, $contract);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_company_show', ['id' => $company->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update contract %contract% from company %company%', [
                '%contract%' => $contract->getOriginalFileName(),
                '%company%' => $company->getName(),
            ], 'company'),
            'form' => $form,
        ]);
    }

    #[Route(path: '/{id}/remove', name: 'app_company_contract_remove', requirements: ['id' => '\d+'])]
    #[IsGranted(User::ROLE_ADMIN)]
    public function remove(Request $request, RemoveFormHandler $removeFormHandler, Contract $contract): Response
    {
        $company = $contract->getCompany();
        $this->denyAccessUnlessGranted(CompanyVoter::COMPANY_ADMIN, $company);

        $formHandlerResponse = $this->formHandlerManager->createAndHandle(
            $removeFormHandler,
            $request,
            $contract
        );

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            $this->uploadFileManager->remove($contract);

            return $this->redirectToRoute('app_company_show', ['id' => $company->getId()]);
        }

        return $this->render('shared/common/remove.html.twig', [
            'title' => new TranslatableMessage('Remove contract %contract% from company %company%?', [
                '%contract%' => $contract->getOriginalFileName(),
                '%company%' => $company->getName(),
            ], 'company'),
            'form' => $form,
        ]);
    }

    #[Route(path: '/{id}/download', name: 'app_company_contract_download', requirements: ['id' => '\d+'])]
    public function download(Contract $contract): BinaryFileResponse
    {
        return $this->uploadFileManager->download($contract);
    }

    private function getFormHandlerResponse(Request $request, Company $company, ?Contract $contract): FormHandlerResponseInterface
    {
        $dto = $this->companyContractFormDtoFactory->create($company, $contract);

        return $this->formHandlerManager->createAndHandle(
            $this->companyContractFormHandler,
            $request,
            $dto
        );
    }
}
