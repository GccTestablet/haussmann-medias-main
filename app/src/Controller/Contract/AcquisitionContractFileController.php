<?php

declare(strict_types=1);

namespace App\Controller\Contract;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Contract\AcquisitionContractFile;
use App\Form\Handler\Common\RemoveFormHandler;
use App\Tools\Manager\UploadFileManager;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatableMessage;

#[Route(path: '/acquisition-contracts/{contract}/files', requirements: ['contract' => '\d+'])]
class AcquisitionContractFileController extends AbstractAppController
{
    public function __construct(
        private readonly UploadFileManager $uploadFileManager,
    ) {}

    #[Route(path: '/{file}/download', name: 'app_acquisition_contract_file_download', requirements: ['file' => '\d+'])]
    public function download(AcquisitionContractFile $file): BinaryFileResponse
    {
        return $this->uploadFileManager->download($file);
    }

    #[Route(path: '/{file}/remove', name: 'app_acquisition_contract_file_remove', requirements: ['file' => '\d+'])]
    public function remove(Request $request, RemoveFormHandler $removeFormHandler, AcquisitionContractFile $file): Response
    {
        $formHandlerResponse = $this->formHandlerManager->createAndHandle(
            $removeFormHandler,
            $request,
            $file
        );

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            $this->uploadFileManager->remove($file);

            return $this->redirectToRoute('app_acquisition_contract_update', [
                'id' => $file->getAcquisitionContract()->getId(),
            ]);
        }

        return $this->render('shared/common/remove.html.twig', [
            'title' => new TranslatableMessage('Remove file %file% from distributin contract %contract%', [
                '%file%' => $file->getOriginalFileName(),
                '%contract%' => $file->getAcquisitionContract()->getName(),
            ], 'contract'),
            'form' => $form,
        ]);
    }
}
