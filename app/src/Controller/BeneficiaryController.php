<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Shared\AbstractAppController;
use App\Entity\Beneficiary;
use App\Entity\User;
use App\Form\Dto\Beneficiary\BeneficiaryFormDto;
use App\Form\DtoFactory\Beneficiary\BeneficiaryFormDtoFactory;
use App\Form\Handler\Beneficiary\BeneficiaryFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Service\Beneficiary\BeneficiaryManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[Route('/beneficiaries')]
class BeneficiaryController extends AbstractAppController
{
    public function __construct(
        private readonly BeneficiaryFormDtoFactory $beneficiaryFormDtoFactory,
        private readonly BeneficiaryFormHandler $beneficiaryFormHandler,
        private readonly BeneficiaryManager $beneficiaryManager
    ) {}

    #[Route(name: 'app_beneficiary_index')]
    public function index(): Response
    {
        return $this->render('beneficiary/index.html.twig', [
            'beneficiaries' => $this->beneficiaryManager->findAll(),
        ]);
    }

    #[Route(path: '/{id}', name: 'app_beneficiary_show', requirements: ['id' => '\d+'])]
    public function show(Beneficiary $beneficiary): Response
    {
        return $this->render('beneficiary/show.html.twig', [
            'beneficiary' => $beneficiary,
        ]);
    }

    #[Route('/add', name: 'app_beneficiary_add')]
    #[IsGranted(User::ROLE_SUPER_ADMIN)]
    public function add(Request $request): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, null);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var BeneficiaryFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_beneficiary_show', ['id' => $dto->getBeneficiary()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Add beneficiary', [], 'beneficiary'),
            'form' => $form,
        ]);
    }

    #[Route('/{id}/update', name: 'app_beneficiary_update', requirements: ['id' => '\d+'])]
    #[IsGranted(User::ROLE_SUPER_ADMIN)]
    public function update(Request $request, Beneficiary $beneficiary): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, $beneficiary);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var BeneficiaryFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_beneficiary_show', ['id' => $dto->getBeneficiary()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update beneficiary %name%', ['%name%' => $beneficiary->getName()], 'beneficiary'),
            'form' => $form,
        ]);
    }

    private function getFormHandlerResponse(Request $request, ?Beneficiary $beneficiary): FormHandlerResponseInterface
    {
        $dto = $this->beneficiaryFormDtoFactory->create($beneficiary);

        return $this->formHandlerManager->createAndHandle(
            $this->beneficiaryFormHandler,
            $request,
            $dto
        );
    }
}
