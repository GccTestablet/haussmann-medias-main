<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Controller\Shared\AbstractAppController;
use App\Form\Dto\Security\LoginFormDto;
use App\Form\Type\Security\LoginFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractAppController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $login = (new LoginFormDto())
            ->setEmail($lastUsername)
        ;

        return $this->render('security/login.html.twig', [
            'form' => $this->createForm(LoginFormType::class, $login),
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): never
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
