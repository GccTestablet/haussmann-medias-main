<?php

declare(strict_types=1);

namespace App\Form\Handler\Shared;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface FormHandlerInterface
{
    public function configureOptions(OptionsResolver $optionsResolver): void;

    /**
     * @param array<string, mixed> $options
     */
    public function create(object $data = null, array $options = []): FormInterface;

    /**
     * @param array<string, mixed> $options
     */
    public function handle(Request $request, FormInterface $form, array $options = []): FormHandlerResponseInterface;
}
