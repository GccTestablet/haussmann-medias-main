<?php

declare(strict_types=1);

namespace App\Controller\Contract;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Company;
use App\Entity\Contract\AcquisitionContract;
use App\Entity\User;
use App\Enum\Pager\ColumnEnum;
use App\Form\Dto\Contract\AcquisitionContractFormDto;
use App\Form\DtoFactory\Contract\AcquisitionContractFormDtoFactory;
use App\Form\Handler\Common\RemoveFormHandler;
use App\Form\Handler\Contract\AcquisitionContractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Model\Pager\Filter;
use App\Pager\Contract\AcquisitionContractPager;
use App\Pager\Contract\AcquisitionContractWorkPager;
use App\Security\Voter\CompanyVoter;
use App\Service\Security\SecurityManager;
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
        private readonly SecurityManager $securityManager,
        private readonly UploadFileManager $uploadFileManager,
        private readonly AcquisitionContractFormHandler $formHandler,
        private readonly AcquisitionContractFormDtoFactory $formDtoFactory,
        private readonly AcquisitionContractPager $acquisitionContractPager,
        private readonly AcquisitionContractWorkPager $acquisitionContractWorkPager,
    ) {}

    #[Route(name: 'app_acquisition_contract_index')]
    public function index(Request $request): Response
    {
        $user = $this->securityManager->getConnectedUser();
        $company = $user->getConnectedOn();
        if (!$company instanceof Company) {
            $this->createAccessDeniedException('You must be connected on a company to access this page');
        }

        $pagerResponse = $this->pagerManager->create(
            $this->acquisitionContractPager,
            $request,
            [
                new Filter(ColumnEnum::COMPANY, $company),
            ]
        );

        return $this->render('acquisition_contract/index.html.twig', [
            'pagerResponse' => $pagerResponse,
        ]);
    }

    #[Route(path: '/{id}', name: 'app_acquisition_contract_show', requirements: ['id' => '\d+'])]
    public function show(Request $request, AcquisitionContract $contract): Response
    {
        $workPagerResponse = $this->pagerManager->create(
            $this->acquisitionContractWorkPager,
            $request,
            [
                new Filter(ColumnEnum::ACQUISITION_CONTRACT, $contract),
            ]
        );

        return $this->render('acquisition_contract/show.html.twig', [
            'contract' => $contract,
            'workPagerResponse' => $workPagerResponse,
        ]);
    }

    #[Route(path: '/add', name: 'app_acquisition_contract_add', requirements: ['id' => '\d+'])]
    public function add(Request $request): Response
    {
        $user = $this->securityManager->getConnectedUser();
        $company = $user->getConnectedOn();
        if (!$company instanceof Company) {
            $this->createAccessDeniedException('You must be connected on a company to access this page');
        }

        $formHandlerResponse = $this->getFormHandlerResponse($request, $company, null);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var AcquisitionContractFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_acquisition_contract_show', [
                'id' => $dto->getContract()->getId(),
            ]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Add contract to company %name%', [
                '%name%' => $company->getName(),
            ], 'company'),
            'form' => $form,
            'backUrl' => $this->generateUrl('app_acquisition_contract_index'),
        ]);
    }

    #[Route(path: '/{id}/update', name: 'app_acquisition_contract_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, AcquisitionContract $contract): Response
    {
        $company = $contract->getCompany();
        $this->denyAccessUnlessGranted(CompanyVoter::COMPANY_ADMIN, $company);

        $formHandlerResponse = $this->getFormHandlerResponse($request, $company, $contract);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_acquisition_contract_show', [
                'id' => $contract->getId(),
            ]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update contract %contract% from company %company%', [
                '%contract%' => $contract->getName(),
                '%company%' => $company->getName(),
            ], 'company'),
            'form' => $form,
            'backUrl' => $this->generateUrl('app_acquisition_contract_index'),
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

    private function getFormHandlerResponse(Request $request, Company $company, ?AcquisitionContract $contract): FormHandlerResponseInterface
    {
        $dto = $this->formDtoFactory->create($company, $contract);

        return $this->formHandlerManager->createAndHandle(
            $this->formHandler,
            $request,
            $dto
        );
    }
}
