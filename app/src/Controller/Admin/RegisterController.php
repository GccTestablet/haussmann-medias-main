<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Shared\AbstractAppController;
use App\Form\Dto\Admin\RegisterFormDto;
use App\Form\Handler\Admin\RegisterFormHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin')]
class RegisterController extends AbstractAppController
{
    #[Route('/register', name: 'app_admin_register')]
    public function register(Request $request, RegisterFormHandler $registerFormHandler): Response
    {
        $registerFormDto = new RegisterFormDto();
        $formHandlerResponse = $this->formHandlerManager->createAndHandle(
            $registerFormHandler,
            $request,
            $registerFormDto
        );

        $form = $formHandlerResponse->getForm();
        if ($formHandlerResponse->isSuccessful()) {
            /** @var RegisterFormDto $dto */
            $dto = $form->getData();

            return $this->redirect($this->generateUrl('app_user_show', ['id' => $dto->getUser()?->getId()]));
        }

        return $this->render('admin/register.html.twig', [
            'form' => $form,
        ]);
    }
}
