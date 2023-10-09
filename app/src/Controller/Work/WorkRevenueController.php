<?php

declare(strict_types=1);

namespace App\Controller\Work;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Work;
use App\Entity\WorkRevenue;
use App\Form\DtoFactory\Work\WorkRevenueFormDtoFactory;
use App\Form\Handler\Common\RemoveFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Handler\Work\WorkRevenueFormHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/works/{work}/revenues', requirements: ['work' => '\d+'])]
class WorkRevenueController extends AbstractAppController
{
    public function __construct(
        private readonly WorkRevenueFormDtoFactory $workRevenueFormDtoFactory,
        private readonly WorkRevenueFormHandler $workRevenueFormHandler,
    ) {}

    #[Route(path: '/{id}', name: 'app_work_revenue_show', requirements: ['id' => '\d+'])]
    public function show(WorkRevenue $workRevenue): Response
    {
        return $this->render('work/revenue/show.html.twig', [
            'revenue' => $workRevenue,
        ]);
    }

    #[Route('/add', name: 'app_work_revenue_add')]
    public function add(Request $request, Work $work): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, null, $work);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_work_show', ['id' => $work->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Add revenue to work %work%', [
                '%work%' => $work->getName(),
            ], 'work'),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: 'app_work_revenue_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, WorkRevenue $workRevenue): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, $workRevenue, $workRevenue->getWork());

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_work_show', ['id' => $workRevenue->getWork()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update revenue from work %work%', [
                '%work%' => $workRevenue->getWork()->getName(),
            ], 'work'),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/remove', name: 'app_work_revenue_remove', requirements: ['id' => '\d+'])]
    public function remove(Request $request, RemoveFormHandler $removeFormHandler, WorkRevenue $workRevenue): Response
    {
        $formHandlerResponse = $this->formHandlerManager->createAndHandle(
            $removeFormHandler,
            $request,
            $workRevenue
        );

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_work_show', ['id' => $workRevenue->getWork()->getId()]);
        }

        return $this->render('shared/common/remove.html.twig', [
            'title' => new TranslatableMessage('Remove revenue from work %work%', [
                '%work%' => $workRevenue->getWork()->getName(),
            ], 'work'),
            'form' => $form,
        ]);
    }

    private function getFormHandlerResponse(Request $request, ?WorkRevenue $workRevenue, Work $work): FormHandlerResponseInterface
    {
        $dto = $this->workRevenueFormDtoFactory->create($work, $workRevenue);

        return $this->formHandlerManager->createAndHandle(
            $this->workRevenueFormHandler,
            $request,
            $dto
        );
    }
}
