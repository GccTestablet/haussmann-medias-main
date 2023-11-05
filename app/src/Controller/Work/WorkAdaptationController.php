<?php

declare(strict_types=1);

namespace App\Controller\Work;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Work\Work;
use App\Entity\Work\WorkAdaptation;
use App\Form\DtoFactory\Work\WorkAdaptationFormDtoFactory;
use App\Form\Handler\Common\RemoveFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Handler\Work\WorkAdaptationFormHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/works/{work}/distribution-costs', requirements: ['work' => '\d+'])]
class WorkAdaptationController extends AbstractAppController
{
    public function __construct(
        private readonly WorkAdaptationFormDtoFactory $workAdaptationFormDtoFactory,
        private readonly WorkAdaptationFormHandler $workAdaptationFormHandler,
    ) {}

    #[Route(path: '/{id}', name: 'app_work_adaptation_show', requirements: ['id' => '\d+'])]
    public function show(WorkAdaptation $workAdaptation): Response
    {
        return $this->render('work/adaptation/show.html.twig', [
            'workAdaptation' => $workAdaptation,
        ]);
    }

    #[Route('/add', name: 'app_work_adaptation_add')]
    public function add(Request $request, Work $work): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, null, $work);

        $form = $formHandlerResponse->getForm();
        $redirectUrl = $this->generateUrl('app_work_show', [
            'id' => $work->getId(),
            'tab' => 'distribution-costs',
        ]);
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirect($redirectUrl);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Add adaptation to work %work%', [
                '%work%' => $work->getName(),
            ], 'work'),
            'form' => $form,
            'backUrl' => $redirectUrl,
        ]);
    }

    #[Route('/{id}/update', name: 'app_work_adaptation_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, WorkAdaptation $workAdaptation): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, $workAdaptation, $workAdaptation->getWork());

        $form = $formHandlerResponse->getForm();
        $redirectUrl = $this->generateUrl('app_work_show', [
            'id' => $workAdaptation->getWork()->getId(),
            'tab' => 'distribution-costs',
        ]);
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirect($redirectUrl);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update adaptation from work %work%', [
                '%work%' => $workAdaptation->getWork()->getName(),
            ], 'work'),
            'form' => $form,
            'backUrl' => $redirectUrl,
        ]);
    }

    #[Route('/{id}/remove', name: 'app_work_adaptation_remove', requirements: ['id' => '\d+'])]
    public function remove(Request $request, RemoveFormHandler $removeFormHandler, WorkAdaptation $workAdaptation): Response
    {
        $formHandlerResponse = $this->formHandlerManager->createAndHandle(
            $removeFormHandler,
            $request,
            $workAdaptation
        );

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_work_show', ['id' => $workAdaptation->getWork()->getId()]);
        }

        return $this->render('shared/common/remove.html.twig', [
            'title' => new TranslatableMessage('Remove adaptation from work %work%', [
                '%work%' => $workAdaptation->getWork()->getName(),
            ], 'work'),
            'form' => $form,
        ]);
    }

    private function getFormHandlerResponse(Request $request, ?WorkAdaptation $workAdaptation, Work $work): FormHandlerResponseInterface
    {
        $dto = $this->workAdaptationFormDtoFactory->create($work, $workAdaptation);

        return $this->formHandlerManager->createAndHandle(
            $this->workAdaptationFormHandler,
            $request,
            $dto
        );
    }
}
