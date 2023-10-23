<?php

declare(strict_types=1);

namespace App\Controller\Company;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Company;
use App\Entity\Contract\AcquisitionContract;
use App\Entity\User;
use App\Form\Dto\Contract\AcquisitionContractFormDto;
use App\Form\DtoFactory\Contract\AcquisitionContractFormDtoFactory;
use App\Form\Handler\Contract\AcquisitionContractFormHandler;
use App\Security\Voter\CompanyVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[Route('/companies/{company}/acquisition-contracts')]
class CompanyAcquisitionContractController extends AbstractAppController
{
    public function __construct(
        private readonly AcquisitionContractFormHandler $companyContractFormHandler,
        private readonly AcquisitionContractFormDtoFactory $companyContractFormDtoFactory,
    ) {}

    #[Route(name: 'app_company_acquisition_contract_index')]
    public function index(Company $company): Response
    {
        return $this->render('acquisition_contract/index.html.twig', [
            'company' => $company,
            'contracts' => $company->getAcquisitionContracts(),
        ]);
    }

    #[Route(path: '/{id}', name: 'app_company_acquisition_contract_show', requirements: ['id' => '\d+'])]
    public function show(AcquisitionContract $contract): Response
    {
        return $this->render('acquisition_contract/show.html.twig', [
            'contract' => $contract,
        ]);
    }

    #[Route(path: '/add', name: 'app_company_acquisition_contract_add')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function add(Request $request, Company $company): Response
    {
        $this->denyAccessUnlessGranted(CompanyVoter::COMPANY_ADMIN, $company);

        $dto = $this->companyContractFormDtoFactory->create($company, null);
        $formHandlerResponse = $this->formHandlerManager->createAndHandle(
            $this->companyContractFormHandler,
            $request,
            $dto
        );

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var AcquisitionContractFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_company_acquisition_contract_show', [
                'company' => $company->getId(),
                'id' => $dto->getContract()->getId(),
            ]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Add contract to company %name%', ['%name%' => $company->getName()], 'company'),
            'form' => $form,
        ]);
    }
}
