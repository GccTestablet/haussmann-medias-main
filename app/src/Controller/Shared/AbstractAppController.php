<?php

declare(strict_types=1);

namespace App\Controller\Shared;

use App\Form\Handler\Shared\FormHandlerManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\TwigComponent\ComponentRendererInterface;

abstract class AbstractAppController extends AbstractController
{
    final public const FLASH_SUCCESS = 'success';

    #[Required]
    public FormHandlerManager $formHandlerManager;

    #[Required]
    public TranslatorInterface $translator;

    #[Required]
    public ComponentRendererInterface $componentRenderer;

    public function renderComponent(string $name, array $parameters = []): Response
    {
        $component = $this->componentRenderer->createAndRender($name, $parameters);

        return new Response($component);
    }
}
