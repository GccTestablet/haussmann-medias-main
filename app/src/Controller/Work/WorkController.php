<?php

declare(strict_types=1);

namespace App\Controller\Work;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Contract\AcquisitionContract;
use App\Entity\Work\Work;
use App\Enum\Pager\ColumnEnum;
use App\Form\DtoFactory\Work\WorkFormDtoFactory;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Handler\Work\WorkFormHandler;
use App\Model\Pager\Filter;
use App\Model\Pager\FilterCollection;
use App\Pager\Contract\DistributionContractPager;
use App\Pager\Work\WorkAdaptationCostPager;
use App\Pager\Work\WorkPager;
use App\Pager\Work\WorkReversionPager;
use App\Pager\Work\WorkTerritoryPager;
use App\Service\Security\SecurityManager;
use App\Tools\Parser\StringParser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/works')]
class WorkController extends AbstractAppController
{
    public function __construct(
        private readonly SecurityManager $securityManager,
        private readonly WorkPager $workPager,
        private readonly WorkFormDtoFactory $workFormDtoFactory,
        private readonly WorkFormHandler $workFormHandler,
        private readonly DistributionContractPager $distributionContractPager,
        private readonly WorkTerritoryPager $workTerritoryPager,
        private readonly WorkAdaptationCostPager $workAdaptationCostPager,
        private readonly WorkReversionPager $workReversionPager,
    ) {}

    #[Route(name: 'app_work_index')]
    public function index(Request $request): Response
    {
        $pagerResponse = $this->pagerManager->create(
            $this->workPager,
            $request,
            (new FilterCollection())
                ->addFilter(
                    new Filter(
                        ColumnEnum::COMPANY,
                        $this->securityManager->getConnectedUser()->getConnectedOn()
                    )
                )
        );

        return $this->render('work/index.html.twig', [
            'pagerResponse' => $pagerResponse,
        ]);
    }

    #[Route(path: '/{id}/{tab}', name: 'app_work_show', requirements: ['id' => '\d+', 'tab' => 'territories|distribution-costs|reversions|distribution-contracts'], defaults: ['tab' => null])]
    public function show(Request $request, Work $work, string $tab = null): Response
    {
        if (!$tab) {
            return $this->redirectToRoute('app_work_show', [
                'id' => $work->getId(),
                'tab' => 'territories',
            ]);
        }

        $params['pagerResponse'] = match ($tab) {
            'territories' => $this->pagerManager->create(
                $this->workTerritoryPager,
                $request,
                (new FilterCollection())
                    ->addFilter(new Filter(ColumnEnum::WORK, $work))
            ),
            'distribution-costs' => $this->pagerManager->create(
                $this->workAdaptationCostPager,
                $request,
                (new FilterCollection())
                    ->addFilter(new Filter(ColumnEnum::WORK, $work))
            ),
            'reversions' => $this->pagerManager->create(
                $this->workReversionPager,
                $request,
                (new FilterCollection())
                    ->addFilter(new Filter(ColumnEnum::WORK, $work))
            ),
            'distribution-contracts' => $this->pagerManager->create(
                $this->distributionContractPager,
                $request,
                (new FilterCollection())
                    ->addFilter(new Filter(ColumnEnum::WORKS, [$work]))
            ),
            default => null,
        };

        return $this->render(\sprintf('work/tab/%s.html.twig', StringParser::slugify($tab, '_')), [
            'work' => $work,
            'tab' => $tab,
            ...$params,
        ]);
    }

    #[Route('/{id}/update', name: 'app_work_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, Work $work): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, $work->getAcquisitionContract(), $work);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_work_show', ['id' => $work->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update work %name%', ['%name%' => $work->getName()], 'work'),
            'form' => $form,
        ]);
    }

    private function getFormHandlerResponse(Request $request, AcquisitionContract $contract, ?Work $work): FormHandlerResponseInterface
    {
        $dto = $this->workFormDtoFactory->create($work, $contract);

        return $this->formHandlerManager->createAndHandle(
            $this->workFormHandler,
            $request,
            $dto
        );
    }
}
