<?php

declare(strict_types=1);

namespace App\Controller\Work;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Work;
use App\Entity\WorkReversion;
use App\Form\DtoFactory\Work\WorkReversionFormDtoFactory;
use App\Form\Handler\Common\RemoveFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Handler\Work\WorkReversionFormHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/works/{work}/reversions', requirements: ['work' => '\d+'])]
class WorkReversionController extends AbstractAppController
{
    public function __construct(
        private readonly WorkReversionFormDtoFactory $workReversionFormDtoFactory,
        private readonly WorkReversionFormHandler $workReversionFormHandler,
    ) {}

    #[Route(path: '/{id}', name: 'app_work_reversion_show', requirements: ['id' => '\d+'])]
    public function show(WorkReversion $workReversion): Response
    {
        return $this->render('work/reversion/show.html.twig', [
            'workReversion' => $workReversion,
        ]);
    }

    #[Route('/add', name: 'app_work_reversion_add')]
    public function add(Request $request, Work $work): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, null, $work);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_work_show', ['id' => $work->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Add reversion to work %work%', [
                '%work%' => $work->getName(),
            ], 'work'),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: 'app_work_reversion_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, WorkReversion $workReversion): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, $workReversion, $workReversion->getWork());

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_work_show', ['id' => $workReversion->getWork()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update reversion from work %work%', [
                '%work%' => $workReversion->getWork()->getName(),
            ], 'work'),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/remove', name: 'app_work_reversion_remove', requirements: ['id' => '\d+'])]
    public function remove(Request $request, RemoveFormHandler $removeFormHandler, WorkReversion $workReversion): Response
    {
        $formHandlerResponse = $this->formHandlerManager->createAndHandle(
            $removeFormHandler,
            $request,
            $workReversion
        );

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_work_show', ['id' => $workReversion->getWork()->getId()]);
        }

        return $this->render('shared/common/remove.html.twig', [
            'title' => new TranslatableMessage('Remove reversion from work %work%', [
                '%work%' => $workReversion->getWork()->getName(),
            ], 'work'),
            'form' => $form,
        ]);
    }

    private function getFormHandlerResponse(Request $request, ?WorkReversion $workReversion, Work $work): FormHandlerResponseInterface
    {
        $dto = $this->workReversionFormDtoFactory->create($work, $workReversion);

        return $this->formHandlerManager->createAndHandle(
            $this->workReversionFormHandler,
            $request,
            $dto
        );
    }
}
