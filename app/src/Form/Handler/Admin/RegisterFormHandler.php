<?php

declare(strict_types=1);

namespace App\Form\Handler\Admin;

use App\Form\Dto\Admin\RegisterFormDto;
use App\Form\DtoFactory\Admin\RegisterFormDtoFactory;
use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Admin\RegisterFormType;
use App\Tools\Generator\PasswordGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterFormHandler extends AbstractFormHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly RegisterFormDtoFactory $registerFormDtoFactory,
    ) {}

    protected static string $form = RegisterFormType::class;

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $dto = $form->getData();
        if (!$dto instanceof RegisterFormDto) {
            throw new \InvalidArgumentException();
        }

        $plainPassword = PasswordGenerator::generate(10);
        $user = $this->registerFormDtoFactory->createUser($dto);

        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
        $dto->setUser($user);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
