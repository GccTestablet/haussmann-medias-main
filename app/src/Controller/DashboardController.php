<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Shared\AbstractAppController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractAppController
{
    #[Route('/', name: '_welcome')]
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
