<?php

declare(strict_types=1);

namespace App\Controller\Work;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Work\Work;
use App\Service\Security\SecurityManager;
use App\Service\Work\WorkManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/works')]
class WorkController extends AbstractAppController
{
    public function __construct(
        private readonly SecurityManager $securityManager,
        private readonly WorkManager $workManager
    ) {}

    #[Route(name: 'app_work_index')]
    public function index(): Response
    {
        $user = $this->securityManager->getConnectedUser();

        return $this->render('work/index.html.twig', [
            'works' => $this->workManager->findByCompany(
                $user->getConnectedOn()
            ),
        ]);
    }

    #[Route(path: '/{id}', name: 'app_work_show', requirements: ['id' => '\d+'])]
    public function show(Work $work): Response
    {
        return $this->render('work/show.html.twig', [
            'work' => $work,
        ]);
    }
}
