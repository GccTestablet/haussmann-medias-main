<?php

declare(strict_types=1);

namespace App\Controller\Contract;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Company;
use App\Entity\Contract\DistributionContract;
use App\Entity\User;
use App\Enum\Pager\ColumnEnum;
use App\Form\Dto\Contract\DistributionContractFormDto;
use App\Form\DtoFactory\Contract\DistributionContractFormDtoFactory;
use App\Form\Handler\Contract\DistributionContractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Model\Pager\Filter;
use App\Model\Pager\FilterCollection;
use App\Pager\Contract\DistributionContractPager;
use App\Pager\Contract\DistributionContractRevenuePager;
use App\Security\Voter\CompanyVoter;
use App\Service\Contract\DistributionContractWorkManager;
use App\Service\Contract\DistributionContractWorkRevenueImporter;
use App\Service\Security\SecurityManager;
use App\Tools\Manager\UploadFileManager;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/distribution-contracts')]
class DistributionContractController extends AbstractAppController
{
    public function __construct(
        private readonly SecurityManager $securityManager,
        private readonly UploadFileManager $uploadFileManager,
        private readonly DistributionContractWorkRevenueImporter $distributionContractTemplateGenerator,
        private readonly DistributionContractWorkManager $distributionContractWorkManager,
        private readonly DistributionContractFormDtoFactory $formDtoFactory,
        private readonly DistributionContractFormHandler $formHandler,
        private readonly DistributionContractRevenuePager $distributionContractRevenuePager,
        private readonly DistributionContractPager $distributionContractPager,
    ) {}

    #[Route(name: 'app_distribution_contract_index')]
    public function index(Request $request): Response
    {
        $user = $this->securityManager->getConnectedUser();
        $company = $user->getConnectedOn();
        if (!$company instanceof Company) {
            $this->createAccessDeniedException('You must be connected on a company to access this page');
        }

        $pagerResponse = $this->pagerManager->create(
            $this->distributionContractPager,
            $request,
            (new FilterCollection())
                ->addFilter(new Filter(ColumnEnum::COMPANY, $company))
        );

        return $this->render('distribution_contract/index.html.twig', [
            'pagerResponse' => $pagerResponse,
        ]);
    }

    #[Route(path: '/{id}/{tab}', name: 'app_distribution_contract_show', requirements: ['id' => '\d+', 'tab' => 'works|revenues'], defaults: ['tab' => null])]
    public function show(Request $request, DistributionContract $contract, string $tab = null): Response
    {
        if (!$tab) {
            return $this->redirectToRoute('app_distribution_contract_show', ['id' => $contract->getId(), 'tab' => 'works']);
        }

        $revenuePagerResponse = $this->pagerManager->create(
            $this->distributionContractRevenuePager,
            $request,
            (new FilterCollection())
                ->addFilter(new Filter(ColumnEnum::DISTRIBUTION_CONTRACT, $contract)),
        );

        return $this->render(\sprintf('distribution_contract/tab/%s.html.twig', $tab), [
            'revenuePagerResponse' => $revenuePagerResponse,
            'tab' => $tab,
            'contract' => $contract,
            'contractWorks' => $this->distributionContractWorkManager->findByDistributionContract($contract),
        ]);
    }

    #[Route(path: '/add', name: 'app_distribution_contract_add')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function add(Request $request): Response
    {
        $user = $this->securityManager->getConnectedUser();
        $company = $user->getConnectedOn();
        if (!$company instanceof Company) {
            $this->createAccessDeniedException('You must be connected on a company to access this page');
        }

        $this->denyAccessUnlessGranted(CompanyVoter::COMPANY_ADMIN, $company);

        $formHandlerResponse = $this->getFormHandlerResponse($request, $company, null);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var DistributionContractFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_distribution_contract_show', ['id' => $dto->getContract()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Add contract to company %name%', ['%name%' => $company->getName()], 'company'),
            'form' => $form,
            'backUrl' => $this->generateUrl('app_distribution_contract_index'),
        ]);
    }

    #[Route(path: '/{id}/update', name: 'app_distribution_contract_update', requirements: ['id' => '\d+'])]
    #[IsGranted(User::ROLE_ADMIN)]
    public function update(Request $request, DistributionContract $contract): Response
    {
        $company = $contract->getCompany();
        $this->denyAccessUnlessGranted(CompanyVoter::COMPANY_ADMIN, $company);

        $formHandlerResponse = $this->getFormHandlerResponse(
            $request,
            $company,
            $contract
        );

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_distribution_contract_show', ['id' => $contract->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update contract %contract% from company %company%', [
                '%contract%' => $contract->getName(),
                '%company%' => $company->getName(),
            ], 'company'),
            'form' => $form,
            'backUrl' => $this->generateUrl('app_distribution_contract_index'),
        ]);
    }

    #[Route(path: '/{id}/generate-template', name: 'app_distribution_contract_generate_template', requirements: ['id' => '\d+'])]
    public function generateTemplate(DistributionContract $contract): BinaryFileResponse
    {
        $fileName = $this->uploadFileManager->createTempFile(\sprintf('template-%s.csv', $contract->getId()));
        $this->distributionContractTemplateGenerator->build(['contract' => $contract]);
        $this->distributionContractTemplateGenerator->generateTemplate($fileName);

        $response = new BinaryFileResponse($fileName, Response::HTTP_OK, [
            'Cache-Control' => 'private',
            'Content-type' => 'text/csv',
            'Content-Disposition' => \sprintf('attachment; filename="Modèle contrat %s.csv";', $contract->getDistributor()->getName()),
        ]);

        $response->sendHeaders();

        return $response;
    }

    private function getFormHandlerResponse(Request $request, Company $company, ?DistributionContract $contract): FormHandlerResponseInterface
    {
        $dto = $this->formDtoFactory->create($company, $contract);

        return $this->formHandlerManager->createAndHandle(
            $this->formHandler,
            $request,
            $dto
        );
    }
}
