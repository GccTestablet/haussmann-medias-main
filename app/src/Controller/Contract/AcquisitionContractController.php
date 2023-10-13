<?php

declare(strict_types=1);

namespace App\Controller\Contract;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Contract\AcquisitionContract;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/acquisition-contracts')]
class AcquisitionContractController extends AbstractAppController
{
    #[Route(path: '/{id}', name: 'app_acquisition_contract_show', requirements: ['id' => '\d+'])]
    public function show(AcquisitionContract $contract): Response
    {
        return $this->render('acquisition_contract/show.html.twig', [
            'contract' => $contract,
        ]);
    }
}
