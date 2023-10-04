<?php

declare(strict_types=1);

namespace App\Form\Handler\Shared;

use Symfony\Component\Form\FormInterface;

/**
 * This is the response from a form handler after handling a form.
 */
class FormHandlerResponse implements FormHandlerResponseInterface
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(
        private FormInterface $form,
        private readonly bool $isSuccessful,
        private readonly array $parameters = []
    ) {}

    public function hasParameter(string $key): bool
    {
        return isset($this->parameters[$key]);
    }

    public function getParameter(string $key, mixed $default = null): mixed
    {
        if (!\array_key_exists($key, $this->parameters)) {
            return $default;
        }

        return $this->parameters[$key];
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function isSuccessful(): bool
    {
        return $this->isSuccessful;
    }

    public function getForm(): FormInterface
    {
        return $this->form;
    }

    public function setForm(FormInterface $form): static
    {
        $this->form = $form;

        return $this;
    }

    public function getErrors(): array
    {
        $errors = [];
        foreach ($this->form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return $errors;
    }
}
