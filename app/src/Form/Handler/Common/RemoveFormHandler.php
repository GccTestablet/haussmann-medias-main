<?php

declare(strict_types=1);

namespace App\Form\Handler\Common;

use App\Form\Handler\Shared\AbstractFormHandler;
use App\Form\Handler\Shared\FormHandlerResponseInterface;
use App\Form\Type\Common\EmptyFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class RemoveFormHandler extends AbstractFormHandler
{
    protected static string $form = EmptyFormType::class;

    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $data = $form->getData();

        $this->entityManager->remove($data);
        $this->entityManager->flush();

        return parent::onFormSubmitAndValid($request, $form, $options);
    }
}
