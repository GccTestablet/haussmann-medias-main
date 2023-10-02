<?php

declare(strict_types=1);

namespace App\Form\Handler\Shared;

use Symfony\Component\Form\FormInterface;

/**
 * Represents the form handler response
 */
interface FormHandlerResponseInterface
{
    /**
     * Checks if a parameter exist in the bag
     */
    public function hasParameter(string $key): bool;

    /**
     * Gets all parameters
     *
     * @return array<string, mixed>
     */
    public function getParameters(): array;

    /**
     * Gets a specific parameter
     */
    public function getParameter(string $key, mixed $default = null): mixed;

    /**
     * Returns the status of the handling of the form
     */
    public function isSuccessful(): bool;

    /**
     * Get the form which is being handled
     */
    public function getForm(): FormInterface;

    public function setForm(FormInterface $form): self;

    /**
     * @return string[]
     */
    public function getErrors(): array;
}
