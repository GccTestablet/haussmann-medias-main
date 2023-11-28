<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Shared\AbstractAppController;
use App\Entity\User;
use App\Form\Dto\User\UserFormDto;
use App\Form\DtoFactory\User\UserFormDtoFactory;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Handler\User\UserFormHandler;
use App\Security\Voter\UserVoter;
use App\Service\User\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Translation\TranslatableMessage;

#[Route('/users')]
class UserController extends AbstractAppController
{
    public function __construct(
        private readonly UserFormDtoFactory $userFormDtoFactory,
        private readonly UserFormHandler $userFormHandler,
        private readonly UserManager $userManager
    ) {}

    #[Route(name: 'app_user_index')]
    #[IsGranted(User::ROLE_SUPER_ADMIN)]
    public function index(): Response
    {
        $users = $this->userManager->findAll();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route(path: '/{id}', name: 'app_user_show', requirements: ['id' => '\d+'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/add', name: 'app_user_add')]
    #[IsGranted(User::ROLE_SUPER_ADMIN)]
    public function add(Request $request): Response
    {
        $formHandlerResponse = $this->getFormHandlerResponse($request, null);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var UserFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_user_show', ['id' => $dto->getUser()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Add user', [], 'user'),
            'form' => $form,
            'backUrl' => $this->generateUrl('app_user_index'),
        ]);
    }

    #[Route('/{id}/update', name: 'app_user_update', requirements: ['id' => '\d+'])]
    public function update(Request $request, User $user): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::SAME_USER, $user);

        $formHandlerResponse = $this->getFormHandlerResponse($request, $user);

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var UserFormDto $dto */
            $dto = $form->getData();

            return $this->redirectToRoute('app_user_show', ['id' => $dto->getUser()->getId()]);
        }

        return $this->render('shared/common/save.html.twig', [
            'title' => new TranslatableMessage('Update user %name%', ['%name%' => $user->getFullName()], 'user'),
            'form' => $form,
        ]);
    }

    private function getFormHandlerResponse(Request $request, ?User $user): FormHandlerResponseInterface
    {
        $dto = $this->userFormDtoFactory->create($user);

        return $this->formHandlerManager->createAndHandle(
            $this->userFormHandler,
            $request,
            $dto
        );
    }
}
