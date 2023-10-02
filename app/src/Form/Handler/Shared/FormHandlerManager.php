<?php

declare(strict_types=1);

namespace App\Form\Handler\Shared;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormHandlerManager
{
    /**
     * @param array<string, mixed> $options
     */
    public function create(FormHandlerInterface $formHandler, object $data = null, array $options = []): FormInterface
    {
        $options = $this->resolveOptions($formHandler, $options);

        return $formHandler->create($data, $options);
    }

    /**
     * @param array<string, mixed> $options
     */
    public function createAndHandle(FormHandlerInterface $formHandler, Request $request, object $data = null, array $options = []): FormHandlerResponseInterface
    {
        $form = $this->create($formHandler, $data, $options);

        return $formHandler->handle($request, $form, $options);
    }

    /**
     * @param array<string, mixed> $options
     */
    public function handleForm(Request $request, FormInterface $form, FormHandlerInterface $handler, array $options = []): FormHandlerResponseInterface
    {
        $options = $this->resolveOptions($handler, $options);

        return $handler->handle($request, $form, $options);
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return array<string, mixed>
     */
    private function resolveOptions(FormHandlerInterface $formHandler, array $options): array
    {
        $optionsResolver = new OptionsResolver();
        $formHandler->configureOptions($optionsResolver);

        return $optionsResolver->resolve($options);
    }
}
