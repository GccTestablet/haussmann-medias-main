<?php

declare(strict_types=1);

namespace App\Form\Handler\Shared;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractFormHandler implements FormHandlerInterface
{
    final public const CSRF_TOKEN_ID = 'form_intention';

    protected static string $form = '';

    protected FormFactoryInterface $formFactory;

    protected CsrfTokenManagerInterface $csrfTokenManager;

    #[Required]
    public function setFormFactory(FormFactoryInterface $formFactory): void
    {
        $this->formFactory = $formFactory;
    }

    #[Required]
    public function setCsrfTokenManager(CsrfTokenManagerInterface $csrfTokenManager): void
    {
        $this->csrfTokenManager = $csrfTokenManager;
    }

    protected function getForm(): string
    {
        return static::$form;
    }

    public function setForm(string $form): static
    {
        static::$form = $form;

        return $this;
    }

    public function create(object $data = null, array $options = []): FormInterface
    {
        if (!$data) {
            $data = $this->initializeData($options);
        }

        return $this->formFactory->create($this->getForm(), $data, $options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        // Do nothing by default
    }

    public function handle(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        $form = $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // refresh CSRF token (form_intention)
            $this->csrfTokenManager->refreshToken(self::CSRF_TOKEN_ID);

            return $this->onFormSubmitAndValid($request, $form, $options);
        }

        return $this->onFormNotSubmitAndValid($request, $form, $options);
    }

    /**
     * @param array<string, mixed> $options
     */
    protected function initializeData(array $options = []): ?object
    {
        return null;
    }

    /**
     * @param array<string, mixed> $options
     */
    protected function onFormSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        return new FormHandlerResponse($form, true);
    }

    /**
     * @param array<string, mixed> $options
     */
    protected function onFormNotSubmitAndValid(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface
    {
        return new FormHandlerResponse($form, false);
    }
}
