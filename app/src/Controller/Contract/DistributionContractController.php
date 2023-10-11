<?php

declare(strict_types=1);

namespace App\Controller\Contract;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Contract\DistributionContract;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/distribution-contracts')]
class DistributionContractController extends AbstractAppController
{
    #[Route(path: '/{id}', name: 'app_distribution_contract_show', requirements: ['id' => '\d+'])]
    public function show(DistributionContract $contract): Response
    {
        return $this->render('distribution_contract/show.html.twig', [
            'contract' => $contract,
        ]);
    }
}
