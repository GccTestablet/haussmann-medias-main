<?php

declare(strict_types=1);

namespace App\Controller\Company;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Company;
use App\Service\Work\WorkManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/companies/{company}/works')]
class CompanyWorkController extends AbstractAppController
{
    public function __construct(
        private readonly WorkManager $workManager
    ) {}

    #[Route(name: 'app_company_work_index')]
    public function index(Company $company): Response
    {
        return $this->render('work/index.html.twig', [
            'works' => $this->workManager->findByCompany($company),
        ]);
    }
}
