<?php

declare(strict_types=1);

namespace App\Controller\Contract;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Contract\AcquisitionContract;
use App\Entity\User;
use App\Form\DtoFactory\Contract\AcquisitionContractFormDtoFactory;
use App\Form\Handler\Common\RemoveFormHandler;
use App\Form\Handler\Contract\AcquisitionContractFormHandler;
use App\Security\Voter\CompanyVoter;
use App\Tools\Manager\UploadFileManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/acquisition-contracts')]
class AcquisitionContractController extends AbstractAppController
{
    public function __construct(
        private readonly UploadFileManager $uploadFileManager
    ) {}

    #[Route(path: '/{id}', name: 'app_acquisition_contract_show', requirements: ['id' => '\d+'])]
    public function show(AcquisitionContract $contract): Response
    {
        return $this->render('acquisition_contract/show.html.twig', [
            'contract' => $contract,
        ]);
    }

    #[Route(path: '/{id}/update', name: 'app_acquisition_contract_update', requirements: ['id' => '\d+'])]
    #[IsGranted(User::ROLE_ADMIN)]
    public function update(Request $request, AcquisitionContractFormDtoFactory $formDtoFactory, AcquisitionContractFormHandler $formHandler, AcquisitionContract $contract): Response
    {
        $company = $contract->getCompany();
        $this->denyAccessUnlessGranted(CompanyVoter::COMPANY_ADMIN, $company);

        $dto = $formDtoFactory->create($company, $contract);
        $formHandlerResponse = $this->formHandlerManager->createAndHandle(
            $formHandler,
            $request,
            $dto
        );

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_company_acquisition_contract_show', [
                'company' => $company->getId(),
                'id' => $contract->getId(),
            ]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update contract %contract% from company %company%', [
                '%contract%' => $contract->getName(),
                '%company%' => $company->getName(),
            ], 'company'),
            'form' => $form,
        ]);
    }

    #[Route(path: '/{id}/remove', name: 'app_acquisition_contract_remove', requirements: ['id' => '\d+'])]
    #[IsGranted(User::ROLE_ADMIN)]
    public function remove(Request $request, RemoveFormHandler $removeFormHandler, AcquisitionContract $contract): Response
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
            $this->uploadFileManager->removeMultiple($contract->getContractFiles()->toArray());

            return $this->redirectToRoute('app_company_show', ['id' => $company->getId()]);
        }

        return $this->render('shared/common/remove.html.twig', [
            'title' => new TranslatableMessage('Remove contract %contract% from company %company%?', [
                '%contract%' => $contract->getName(),
                '%company%' => $company->getName(),
            ], 'company'),
            'form' => $form,
        ]);
    }
}
