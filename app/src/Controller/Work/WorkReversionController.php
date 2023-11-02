<?php

declare(strict_types=1);

namespace App\Controller\Work;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Work\Work;
use App\Entity\Work\WorkReversion;
use App\Form\DtoFactory\Work\WorkReversionFormDtoFactory;
use App\Form\Handler\Work\WorkReversionFormHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/works/{work}/reversions', requirements: ['work' => '\d+'])]
class WorkReversionController extends AbstractAppController
{
    public function __construct(
        private readonly WorkReversionFormDtoFactory $formDtoFactory,
        private readonly WorkReversionFormHandler $formHandler,
    ) {}

    #[Route(path: '/{id}', name: 'app_work_reversion_show', requirements: ['id' => '\d+'])]
    public function show(WorkReversion $workReversion): Response
    {
        return $this->render('work/reversion/show.html.twig', [
            'workReversion' => $workReversion,
        ]);
    }

    #[Route(path: '/manage', name: 'app_work_reversion_manage')]
    public function manage(Request $request, Work $work): Response
    {
        $dto = $this->formDtoFactory->create($work);

        $formHandlerResponse = $this->formHandlerManager->createAndHandle(
            $this->formHandler,
            $request,
            $dto
        );

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            return $this->redirectToRoute('app_work_show', [
                'id' => $work->getId(),
                'tab' => 'reversions',
            ]);
        }

        return $this->render('work/reversion/manage.html.twig', [
            'work' => $work,
            'form' => $form,
        ]);
    }
}
